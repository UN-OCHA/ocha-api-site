<?php

namespace App\State\KeyFigures;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\Repository\KeyFiguresRepository;
use App\State\KeyFigures\KeyFigureProviderTrait;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class KeyFiguresOchaPresenceFiguresStateProvider implements ProviderInterface
{

    use KeyFigureProviderTrait;

    public function __construct(
        private KeyFiguresRepository $repository,
        private TokenStorageInterface $tokenStorage,
    )
    {
    }

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): object|array|null
    {
        $this->checkProviderAccess($operation, $context);

        $provider = $this->getProvider($operation, $context);
        return $this->repository->getOchaPresenceFigures($provider, $uriVariables['ocha_presence_id'], $uriVariables['year']);
    }
}

