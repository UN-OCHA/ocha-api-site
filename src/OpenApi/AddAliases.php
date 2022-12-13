<?php

namespace App\OpenApi;

use ApiPlatform\Metadata\Resource\Factory\ResourceMetadataCollectionFactoryInterface;
use ApiPlatform\Metadata\Resource\ResourceMetadataCollection;
use App\Repository\ProviderRepository;

class AddAliases implements ResourceMetadataCollectionFactoryInterface
{
    public function __construct(
        private ResourceMetadataCollectionFactoryInterface $decorated,
        private ProviderRepository $providerRepository,
    )
    {
        $this->decorated = $decorated;
    }

    public function create(string $resourceClass): ResourceMetadataCollection
    {
        //** @var \ApiPlatform\Metadata\Resource\ResourceMetadataCollection $resourceMetadataCollection */
        $resourceMetadataCollection = $this->decorated->create($resourceClass);

        //** @var \ApiPlatform\Metadata\ApiResource $resourceMetadata */
        foreach ($resourceMetadataCollection as $key => $resourceMetadata) {
            if ($resourceMetadata::class !== 'ApiPlatform\Metadata\ApiResource') {
                continue;
            }

            $extra_properties = $resourceMetadata->getExtraProperties();
            if (!isset($extra_properties['expand'])) {
                continue;
            }

            // Load providers.
            $expand = $extra_properties['expand'];
            $providers = $this->providerRepository->findBy([
                'expand' => $expand,
            ]);

            /** @var ApiPlatform\Metadata\ApiResource $resourceMetadata */
            /** @var ApiPlatform\Metadata\Operations $operations */
            $operations = $resourceMetadata->getOperations();
            $it = $operations->getIterator();
            $new_operations = [];

            // Loop all operations
            while ($it->valid()) {
                /** @var ApiPlatform\Metadata\Operation $operation */
                $operation = $it->current();

                // Skip NoOp.
                if ($operation::class === 'ApiPlatform\Metadata\NotExposed') {
                    $it->next();
                    continue;
                }

                // Generate for each provider.
                foreach ($providers as $provider) {
                    $new_key = str_replace($expand, str_replace('-', '_', $provider->getPrefix()), $operation->getName());
                    $openApiContext = $operation->getOpenapiContext();
                    $openApiContext['tags'] = [$provider->getName()];
                    /** @var \App\OpenApi\ApiPlatform\Metadata\Operation $new_operation */
                    $new_operation = $operation->withUriTemplate(str_replace($expand, $provider->getPrefix(), $operation->getUriTemplate()))
                        ->withExtraProperties([
                            'user_defined_uri_template' => TRUE,
                            'provider' => $provider->getId(),
                            'expand' => $expand,
                        ])
                        ->withOpenapiContext($openApiContext)
                        ->withName(str_replace($expand, str_replace('-', '_', $provider->getPrefix()), $operation->getName()))
                    ;

                    $new_operations[$new_key] = $new_operation;
                }

                $it->next();
            }

            // Add all new operations.
            foreach ($new_operations as $new_key => $new_operation) {
                $operations->add($new_key, $new_operation);
            }

            // Update resource.
            $resourceMetadataCollection[$key] = $resourceMetadata->withOperations($operations);
        }

        return $resourceMetadataCollection;
    }
}
