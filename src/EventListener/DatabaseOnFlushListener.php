<?php

namespace App\EventListener;

use App\Entity\KeyFigures;
use App\Entity\User;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Event\OnFlushEventArgs;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class DatabaseOnFlushListener
{

    public function __construct(
      private ManagerRegistry $managerRegistry,
      private HttpClientInterface $httpClient,
      private EntityManagerInterface $entityManager,
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
            $provider = $payload[0]['data']['provider'];

            $query = $this->entityManager->createQueryBuilder()
                ->select('u')
                ->from(User::class, 'u')
                ->where('u.webhook is not null');
            $users = $query->getQuery()->getResult();

            /** @var \App\Entity\User $user */
            foreach ($users as $user) {
                if (!in_array('ROLE_ADMIN', $user->getRoles())) {
                    if (!in_array($provider, $user->getCanRead())) {
                        continue;
                    }
                }

                $endpoint = $user->getWebhook();
                try {
                  $this->httpClient->request('POST', $endpoint, [
                      'json' => ['data' => $payload],
                  ]);
                }
                catch (\Exception $e) {
                  // Ignore it.
                }
            }
        }
    }
}
