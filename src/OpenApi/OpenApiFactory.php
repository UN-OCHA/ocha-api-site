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

        // Hide Get.
        $paths = $openApi->getPaths()->getPaths();
        $filteredPaths = new Model\Paths();
        foreach ($paths as $path => $pathItem) {
            if ($path === '/api/key_figures/{id}') {
                continue;
            }
            $filteredPaths->addPath($path, $pathItem);
        }

        return $openApi->withPaths($filteredPaths);        
        return $openApi;
    }
}