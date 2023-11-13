<?php

namespace App\State\KeyFigures;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Dto\BatchCollection;
use App\State\KeyFigures\KeyFigureProviderTrait;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

final class KeyFiguresBatchProcessor implements ProcessorInterface
{

    use KeyFigureProviderTrait;

    public function __construct(
        private TokenStorageInterface $tokenStorage,
    )
    {
    }

    /**
     * @param BatchCollection $data
     */
    public function process($data, Operation $operation, array $uriVariables = [], array $context = []) : \App\Dto\BatchResponses
    {
        // Trust the responses.
        if ($data::class === 'App\\Dto\\BatchResponses') {
            return $data;
        }

        // Check access.
        $this->checkProviderAccess($operation, $context);

        $provider = $this->getProvider($operation, $context);
        if (!$provider) {
            throw new BadRequestException('Provider not initialized.');
        }

        // Make sure we have data.
        if (!$data) {
            throw new BadRequestException('No data available.');
        }

        if (!$data->data || !is_array($data->data)) {
            throw new BadRequestException('No data available.');
        }

        // Force provider.
        foreach ($data as &$item) {
            if ($item['provider'] !== $provider) {
                throw new BadRequestException('Invalid provider.');
            }
        }

        return $data;
    }

}
