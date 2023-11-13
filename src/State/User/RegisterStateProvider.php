<?php

namespace App\State\User;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Repository\ProviderRepository;

final class RegisterStateProvider implements ProcessorInterface
{
    public function __construct(
        private ProcessorInterface $decorated,
        private ProviderRepository $providersRepo,
    )
    {
    }

    public function process($data, Operation $operation, array $uriVariables = [], array $context = []) : \App\Entity\User
    {
        /** @var \App\Entity\User $data */
        $data->setPassword(bin2hex(random_bytes(32)));
        $data->setToken(bin2hex(random_bytes(16)));
        $data->setCanRead([]);
        $data->setCanWrite([]);
        $data->setProviders([]);

        // Grant access to all resources.
        $providers = $this->providersRepo->findAll();
        $can_read = [];
        foreach ($providers as $provider) {
            $can_read[] = $provider->getId();
        }

        $data->setCanRead($can_read);

        $result = $this->decorated->process($data, $operation, $uriVariables, $context);

        return $result;
    }

}
