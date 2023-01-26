<?php

namespace App\EventListener;

use App\Entity\KeyFigures;
use Doctrine\ORM\Event\OnFlushEventArgs;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class DatabaseOnFlushListener
{

    public function __construct(
      private ManagerRegistry $managerRegistry,
      private HttpClientInterface $httpClient,
    )
    {
    }

    /**
     * @param OnFlushEventArgs $eventArgs
     */
    public function onFlush(OnFlushEventArgs $eventArgs): void
    {
        $payload = [];

        $em = $eventArgs->getObjectManager();
        $uow = $em->getUnitOfWork();
        $uow->computeChangeSets();

        foreach ($uow->getScheduledEntityInsertions() as $entity) {
            if ($entity instanceof KeyFigures) {
                $payload[] = [
                    'status' => 'new',
                    'data' => $entity->extractValues(),
                ];
            }
        }

        foreach ($uow->getScheduledEntityUpdates() as $entity) {
            if ($entity instanceof KeyFigures) {
                $changeSet = $uow->getEntityChangeSet($entity);
                if ($changeSet && isset($changeSet['value']) && $changeSet['value'][0] != $changeSet['value'][1]) {
                    $payload[] = [
                      'status' => 'updated',
                      'old_value' => $changeSet['value'][0],
                      'new_value' => $changeSet['value'][1],
                      'data' => $entity->extractValues(),
                    ];
                }
            }
        }

        if (!empty($payload)) {
            $endpoint = 'http://numbers.docksal.site/webhook/listen';
            $this->httpClient->request('POST', $endpoint, [
              'json' => ['data' => $payload],
            ]);

        }
        trigger_deprecation('payload', '0.0.1', print_r($payload, TRUE));

    }
}
