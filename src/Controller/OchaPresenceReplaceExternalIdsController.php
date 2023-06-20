<?php

namespace App\Controller;

use ApiPlatform\Metadata\Operation;
use App\Dto\ArchiveInput;
use App\Dto\BatchResponses;
use App\Dto\OchaPresenceReplaceExternalIds;
use App\Entity\OchaPresence;
use App\Entity\OchaPresenceExternalId;
use App\Entity\Provider;
use App\Repository\ExternalLookupRepository;
use App\Repository\KeyFiguresRepository;
use App\Repository\OchaPresenceExternalIdRepository;
use App\Repository\OchaPresenceRepository;
use App\Repository\ProviderRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Serializer\SerializerInterface;

class OchaPresenceReplaceExternalIdsController extends AbstractController {

    public function __construct(
        private HttpKernelInterface $kernel,
        private SerializerInterface $serializer,
        private ProviderRepository $providerRepository,
        private ExternalLookupRepository $externalLookupRepository,
        private OchaPresenceExternalIdRepository $ochaPresenceExternalIdRepository
    )
    {
    }

    public function __invoke(Request $request, OchaPresence $data): BatchResponses
    {
        $responses = new BatchResponses;

        /** @var \App\Dto\OchaPresenceReplaceExternalIds $dto */
        $dto = $this->serializer->deserialize($request->getContent(), OchaPresenceReplaceExternalIds::class, 'json');

        $ocha_presence_external_id = $data->getOchaPresenceExternalIdByProviderAndYear($dto->provider, $dto->year);
        if (!$ocha_presence_external_id) {
            $provider = $this->providerRepository->findOneBy(['id' => $dto->provider]);
            $new = new OchaPresenceExternalId();
            $new->setOchaPresence($data);
            $new->setProvider($provider);
            $new->setYear($dto->year);
            foreach ($dto->data as $external_lookup_id) {
                $external_lookup = $this->externalLookupRepository->findOneBy(['id' => $external_lookup_id]);
                $new->addExternalId($external_lookup);
                $responses->successful[$external_lookup_id] = 'added';
            }

            $this->ochaPresenceExternalIdRepository->save($new, TRUE);
        }
        else {
            /** @var \App\Entity\ExternalLookup $external */
            foreach ($ocha_presence_external_id->getExternalIds() as $external) {
                if (!in_array($external->getId(), $dto->data)) {
                    $ocha_presence_external_id->removeExternalId($external);
                    $responses->successful[$external->getId()] = 'removed';
                }
                else {
                    $responses->successful[$external->getId()] = 'present';
                    unset($dto->data[array_search($external->getId(), $dto->data)]);
                }
            }

            if (!empty($dto->data)) {
                foreach ($dto->data as $external_lookup_id) {
                    $external_lookup = $this->externalLookupRepository->findOneBy(['id' => $external_lookup_id]);
                    $ocha_presence_external_id->addExternalId($external_lookup);
                    $responses->successful[$external_lookup_id] = 'added';
                }
            }

            $this->ochaPresenceExternalIdRepository->save($ocha_presence_external_id, TRUE);
        }

        return $responses;
    }

}
