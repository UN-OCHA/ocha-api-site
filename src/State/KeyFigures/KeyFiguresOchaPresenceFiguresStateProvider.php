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

        // Year comes from URI or query parameter.
        $year = $uriVariables['year'] ?? date('Y');

        $figure_ids = [];
        $uri = $context['uri'] ?? '';

        if (!empty($uri)) {
            $query = parse_url($uri, PHP_URL_QUERY);
            parse_str($query, $parsed);

            if (isset($parsed['figure_id'])) {
                $figure_ids = $parsed['figure_id'];
                if (!is_array($figure_ids)) {
                    $figure_ids = [$figure_ids];
                }
            }

            if (isset($parsed['year'])) {
                $year = $parsed['year'];
            }
        }

        $provider = $this->getProvider($operation, $context);
        return $this->repository->getOchaPresenceFigures($provider, $uriVariables['ocha_presence_id'], $year, $figure_ids);
    }
}

