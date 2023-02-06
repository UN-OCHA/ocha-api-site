<?php

namespace App\Controller;

use ApiPlatform\Metadata\Operation;
use App\Dto\BatchCollection;
use App\Dto\BatchResponses;
use App\Entity\KeyFigures;
use App\Repository\KeyFiguresRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class KeyFiguresBatchController extends AbstractController {

    public function __construct(private HttpKernelInterface $kernel)
    {
    }

    public function __invoke(Request $request, BatchCollection $data, KeyFiguresRepository $repository, TokenStorageInterface $tokenStorage): BatchResponses
    {

        $operation = $request->attributes->get('_api_operation');
        $this->checkProviderAccess($operation, $tokenStorage);

        $provider = $this->getProvider($operation);
        if (!$provider) {
            throw new BadRequestException('Provider not initialized.');
        }

        $responses = new BatchResponses;

        foreach ($data->data as $k => $item) {
          if ($existing = $repository->findNotPersisted($item['id'])) {
            if ($existing->getProvider() !== $provider) {
              $responses->failed[$item['id']] = 'Unable to change provider';
            }
            try {
              $existing->fromValues($item);
              $repository->save($existing);
              $responses->successful[$item['id']] = 'Updated';
            }
            catch (\Exception $e) {
              $responses->failed[$item['id']] = $e->getMessage();
            }
          }
          elseif ($existing = $repository->findOneBy(['id' => $item['id']])) {
            if ($existing->getProvider() !== $provider) {
              $responses->failed[$item['id']] = 'Unable to change provider';
            }
            try {
              $existing->fromValues($item);
              $repository->save($existing);
              $responses->successful[$item['id']] = 'Updated';
            }
            catch (\Exception $e) {
              $responses->failed[$item['id']] = $e->getMessage();
            }
          }
          else {
            $new = new KeyFigures();

            try {
              $new->fromValues($item);
              $repository->save($new);
              $responses->successful[$item['id']] = 'Created';
            }
            catch (\Exception $e) {
              $responses->failed[$item['id']] = $e->getMessage();
            }
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
