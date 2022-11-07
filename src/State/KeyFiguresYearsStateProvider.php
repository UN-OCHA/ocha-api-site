<?php

namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\Repository\KeyFiguresRepository;
use App\State\KeyFigureProviderTrait;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class KeyFiguresYearsStateProvider implements ProviderInterface
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
        return $this->repository->getDistinctYears($provider);
    }
}
