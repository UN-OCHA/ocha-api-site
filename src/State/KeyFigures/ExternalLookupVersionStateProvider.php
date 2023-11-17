<?php

namespace App\State\KeyFigures;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\Repository\ExternalLookupVersionRepository;

class ExternalLookupVersionStateProvider implements ProviderInterface
{

    public function __construct(
        private ExternalLookupVersionRepository $repository,
    )
    {
    }

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): array
    {
        $id = $uriVariables['id'];
        return $this->repository->getVersions($id);
    }
}
