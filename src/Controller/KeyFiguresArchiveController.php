<?php

namespace App\Controller;

use ApiPlatform\Metadata\Operation;
use App\Dto\ArchiveInput;
use App\Dto\BatchResponses;
use App\Repository\KeyFiguresRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class KeyFiguresArchiveController extends AbstractController {

    public function __construct(private HttpKernelInterface $kernel)
    {
    }

    public function __invoke(Request $request, ArchiveInput $data, KeyFiguresRepository $repository, TokenStorageInterface $tokenStorage): BatchResponses
    {

        $operation = $request->attributes->get('_api_operation');
        $this->checkProviderAccess($operation, $tokenStorage);

        $provider = $this->getProvider($operation);
        if (!$provider) {
            throw new BadRequestException('Provider not initialized.');
        }

        $criteria = [
          'provider' => $provider,
          'archived' => 0,
        ];
        if (isset($data->iso3) && !empty($data->iso3)) {
          $criteria['iso3'] = $data->iso3;
        }
        if (isset($data->year) && !empty($data->year)) {
          $criteria['year'] = $data->year;
        }

        $records = $repository->findBy($criteria);
        $responses = new BatchResponses;
        foreach ($records as $record) {
          if ($existing = $repository->findOneBy(['id' => $record->getId()])) {
            if ($existing->getProvider() !== $provider) {
              $responses->failed[$record->getId()] = 'Unable to change provider';
            }
            try {
              $existing->setArchived(TRUE);
              $repository->save($existing);
              $responses->successful[$record->getId()] = 'Updated';
            }
            catch (\Exception $e) {
              $responses->failed[$record->getId()] = $e->getMessage();
            }
          }
          else {
            $responses->failed[$record->getId()] = 'Not found';
          }
        }
        $repository->flush();
        return $responses;
    }

    protected function getProvider(Operation $operation) {
      $properties = $operation->getExtraProperties() ?? [];
      return $properties['provider'] ?? NULL;
    }

    protected function checkProviderAccess(Operation $operation, TokenStorageInterface $tokenStorage) {
        $provider = $this->getProvider($operation);

        // Empty provider is allowed.
        if (empty($provider)) {
            return;
        }

        // Check user roles if not admin.
        /** @var \App\Entity\User */
        if ($tokenStorage->getToken() && $user = $tokenStorage->getToken()->getUser()) {
            if (!in_array('ROLE_ADMIN', $user->getRoles())) {
                if (!in_array($provider, $user->getCanWrite())) {
                    throw new AccessDeniedHttpException('This API Key does not have access to this endpoint.');
                }
            }
        }
    }

}
