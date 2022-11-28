<?php

namespace App\Controller;

use App\Dto\BatchCollection;
use App\Dto\BatchResponses;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;

class KeyFiguresBatchController extends AbstractController {

    public function __construct(private HttpKernelInterface $kernel)
    {
    }

    public function __invoke(Request $request, BatchCollection $data): BatchResponses
    {
        $responses = new BatchResponses;

        $headers = $request->headers->all();
        foreach ($data->data as $k => $item) {
            $headers['content-length'] = strlen(json_encode($item));

            $result = $this->executeSubRequest(
                $k,
                str_replace('/batch', '/' . $item['id'], $request->getPathInfo()),
                'PUT',
                $headers,
                json_encode($item)
            );

            if ($result->getStatusCode() !== 200) {
                $body = json_decode($result->getContent(), TRUE);
                $responses->failed[$item['id']] = $result->getStatusCode();
                if (isset($body['detail']) && !empty($body['detail'])) {
                  $responses->failed[$item['id']] .=  ': ' . $body['detail'];
                }
            }
            else {
                $responses->successful[$item['id']] = 'Updated';
            }
        }

        return $responses;
    }

    private function executeSubRequest(int $index, string $path, string $method, array $headers, string $body): Response
    {
        $subRequest = Request::create($path, $method, [], [], [], [], $body);
        $subRequest->headers->replace($headers);

        try {
            return $this->kernel->handle($subRequest, HttpKernelInterface::SUB_REQUEST);
        } catch (\Exception $e) {
            return new Response(sprintf('Batch element #%d failed, check the log files.', $index), 400);
        }
    }
}
