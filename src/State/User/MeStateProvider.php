<?php

namespace App\State\User;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class MeStateProvider implements ProviderInterface
{

    public function __construct(
        private TokenStorageInterface $tokenStorage,
    )
    {
    }

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): object|array|null
    {
        /** @var \App\Entity\User */
        if ($this->tokenStorage->getToken() && $user = $this->tokenStorage->getToken()->getUser()) {
            return $user;
        }
        
        throw new BadRequestException('Unknown user');
    }
}
