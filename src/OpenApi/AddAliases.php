<?php

namespace App\OpenApi;

use ApiPlatform\Metadata\Resource\Factory\ResourceMetadataCollectionFactoryInterface;
use ApiPlatform\Metadata\Resource\ResourceMetadataCollection;

class AddAliases implements ResourceMetadataCollectionFactoryInterface
{
    private $decorated;

    public function __construct(ResourceMetadataCollectionFactoryInterface $decorated)
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

                // @todo read from provider db.
                $new_key = str_replace('key_figures', 'fts', $operation->getName());
                $openApiContext = $operation->getOpenapiContext();
                $openApiContext['tags'] = ['FTS'];
                $new_operation = $operation->withUriTemplate(str_replace('key_figures', 'fts', $operation->getUriTemplate()))
                    ->withExtraProperties([
                        'user_defined_uri_template' => TRUE,
                        'provider' => 'fts',
                    ])
                    ->withOpenapiContext($openApiContext)
                    ->withName(str_replace('key_figures', 'fts', $operation->getName()))
                ;

                $new_operations[$new_key] = $new_operation;
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