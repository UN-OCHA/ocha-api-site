<?php

namespace App\State\KeyFigures;

use ApiPlatform\Metadata\Operation;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

trait KeyFigureProviderTrait {

    protected function getProvider(Operation $operation, array $context = []) {
        $operation = $context['operation'];
        $properties = $operation->getExtraProperties() ?? [];
        return $properties['provider'] ?? NULL;
    }

    protected function checkProviderAccess(Operation $operation, array $context = []) {
        $provider = $this->getProvider($operation, $context);

        // Empty provider is allowed.
        if (empty($provider)) {
            return;
        }

        // Check user roles if not admin.
        /** @var \App\Entity\User */
        if ($this->tokenStorage->getToken() && $user = $this->tokenStorage->getToken()->getUser()) {
            if (!in_array('ROLE_ADMIN', $user->getRoles())) {
                if (!in_array($provider, $user->getCanRead())) {
                    throw new AccessDeniedHttpException('This API Key does not have access to this endpoint.');
                }
            }
        }
    }

}
