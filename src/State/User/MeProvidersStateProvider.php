<?php

namespace App\State\User;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\Repository\ProviderRepository;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class MeProvidersStateProvider implements ProviderInterface
{

    public function __construct(
        private TokenStorageInterface $tokenStorage,
        private ProviderRepository $providersRepo,
    )
    {
    }

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): object|array|null
    {
        /** @var \App\Entity\User */
        if ($this->tokenStorage->getToken() && $user = $this->tokenStorage->getToken()->getUser()) {
            $read = $user->getCanRead();

            $providers = $this->providersRepo->findAll();
            $can_read = [];
            foreach ($providers as $provider) {
              if (in_array($provider->getId(), $read)) {
                $can_read[] = [
                  'id' => $provider->getId(),
                  'prefix' => $provider->getPrefix(),
                  'name' => $provider->getName(),
                ];
              }
            }

            return $can_read;
        }

        throw new BadRequestException('Unknown user');
    }
}
