<?php

namespace App\Controller;

use App\Dto\BatchResponses;
use App\Dto\OchaPresenceReplaceExternalIds;
use App\Entity\OchaPresence;
use App\Entity\OchaPresenceExternalId;
use App\Repository\ExternalLookupRepository;
use App\Repository\OchaPresenceExternalIdRepository;
use App\Repository\ProviderRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpFoundation\Request;
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

        if (empty($dto->externalIds)) {
            $responses->failed['empty'] = 'no ids provided';
            return $responses;
        }

        $ocha_presence_external_id = $data->getOchaPresenceExternalIdByProviderAndYear($dto->provider, $dto->year);
        if (!$ocha_presence_external_id) {
            $provider = $this->providerRepository->findOneBy(['id' => $dto->provider]);
            $new = new OchaPresenceExternalId();
            $new->setOchaPresence($data);
            $new->setProvider($provider);
            $new->setYear($dto->year);
            foreach ($dto->externalIds as $external_lookup_id) {
                $external_lookup = $this->externalLookupRepository->findOneBy(['id' => $external_lookup_id]);
                $new->addExternalId($external_lookup);
                $responses->successful[$external_lookup_id] = 'added';
            }

            $this->ochaPresenceExternalIdRepository->save($new, TRUE);
        }
        else {
            /** @var \App\Entity\ExternalLookup $external */
            foreach ($ocha_presence_external_id->getExternalIds() as $external) {
                if (!in_array($external->getId(), $dto->externalIds)) {
                    $ocha_presence_external_id->removeExternalId($external);
                    $responses->successful[$external->getId()] = 'removed';
                }
                else {
                    $responses->successful[$external->getId()] = 'present';
                    unset($dto->externalIds[array_search($external->getId(), $dto->externalIds)]);
                }
            }

            if (!empty($dto->externalIds)) {
                foreach ($dto->externalIds as $external_lookup_id) {
                    $external_lookup = $this->externalLookupRepository->findOneBy(['id' => $external_lookup_id]);
                    if ($external_lookup) {
                        $ocha_presence_external_id->addExternalId($external_lookup);
                        $responses->successful[$external_lookup_id] = 'added';
                    }
                    else {
                        $responses->successful[$external_lookup_id] = 'does not exist';
                    }
                }
            }

            $this->ochaPresenceExternalIdRepository->save($ocha_presence_external_id, TRUE);
        }

        return $responses;
    }

}
