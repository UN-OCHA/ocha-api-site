<?php

namespace App\State\KeyFigures;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\State\KeyFigures\KeyFigureProviderTrait;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

final class KeyFiguresPutStateProvider implements ProcessorInterface
{

    use KeyFigureProviderTrait;

    public function __construct(
        private ProcessorInterface $decorated,
        private TokenStorageInterface $tokenStorage,
    )
    {
    }

    public function process($data, Operation $operation, array $uriVariables = [], array $context = [])
    {
        $this->checkProviderAccess($operation, $context);

        $provider = $this->getProvider($operation, $context);
        if (!$provider) {
            throw new BadRequestException('Provider not initialized.');
        }

        /** @var \App\Entity\KeyFigures $data */
        $data->setProvider($provider);

        return $this->decorated->process($data, $operation, $uriVariables, $context);
    }

}
