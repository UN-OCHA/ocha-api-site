<?php
namespace App\OpenApi;

use ApiPlatform\OpenApi\Factory\OpenApiFactoryInterface;
use ApiPlatform\OpenApi\OpenApi;
use ApiPlatform\OpenApi\Model;

class OpenApiFactory implements OpenApiFactoryInterface
{
    private $decorated;

    public function __construct(OpenApiFactoryInterface $decorated)
    {
        $this->decorated = $decorated;
    }

    public function __invoke(array $context = []): OpenApi
    {
        $openApi = $this->decorated->__invoke($context);

        // @todo get version from composer.jons
        $openApi = $openApi->withInfo((new Model\Info($openApi->getInfo()->getTitle(), 'v0.0.1', $openApi->getInfo()->getDescription())));

        // Hide useless get operations.
        $paths = $openApi->getPaths()->getPaths();
        $filteredPaths = new Model\Paths();
        foreach ($paths as $path => $pathItem) {
            /** @var \ApiPlatform\OpenApi\Model\PathItem $pathItem */
            if ($pathItem->getGet() && array_key_exists('204', $pathItem->getGet()->getResponses())) {
                continue;
            }

            $filteredPaths->addPath($path, $pathItem);
        }

        return $openApi->withPaths($filteredPaths);        
    }
}