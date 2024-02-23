<?php

declare(strict_types=1);

namespace App\Alice\Purger;

use Doctrine\Common\DataFixtures\Purger\MongoDBPurger as DoctrineMongoDBPurger;
use Doctrine\Common\DataFixtures\Purger\ORMPurger as DoctrineOrmPurger;
use Doctrine\Common\DataFixtures\Purger\PHPCRPurger as DoctrinePhpCrPurger;
use Doctrine\Common\DataFixtures\Purger\PurgerInterface as DoctrinePurgerInterface;
use Doctrine\DBAL\Platforms\MySqlPlatform;
use Doctrine\ODM\MongoDB\DocumentManager as DoctrineMongoDocumentManager;
use Doctrine\ODM\PHPCR\DocumentManager as DoctrinePhpCrDocumentManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectManager;
use Fidry\AliceDataFixtures\Persistence\PurgeMode;
use Fidry\AliceDataFixtures\Persistence\PurgerFactoryInterface;
use Fidry\AliceDataFixtures\Persistence\PurgerInterface;
use InvalidArgumentException;
use Nelmio\Alice\IsAServiceTrait;

/**
 * @final
 */
class VersionPurger implements PurgerInterface, PurgerFactoryInterface
{
    use IsAServiceTrait;

    private ObjectManager $manager;
    private ?PurgeMode $purgeMode;
    private DoctrinePurgerInterface $purger;

    public function __construct(ObjectManager $manager, PurgeMode $purgeMode = null)
    {
        $this->manager = $manager;
        $this->purgeMode = $purgeMode;

        $this->purger = static::createPurger($manager, $purgeMode);
    }

    public function create(PurgeMode $mode, PurgerInterface $purger = null): PurgerInterface
    {
        if (null === $purger) {
            return new self($this->manager, $mode);
        }

        if ($purger instanceof DoctrinePurgerInterface) {
            $manager = $purger->getObjectManager();
        } elseif ($purger instanceof self) {
            $manager = $purger->manager;
        } else {
            throw new InvalidArgumentException(
                sprintf(
                    'Expected purger to be either and instance of "%s" or "%s". Got "%s".',
                    DoctrinePurgerInterface::class,
                    __CLASS__,
                    get_class($purger)
                )
            );
        }

        if (null === $manager) {
            throw new InvalidArgumentException(
                sprintf(
                    'Expected purger "%s" to have an object manager, got "null" instead.',
                    get_class($purger)
                )
            );
        }

        return new self($manager, $mode);
    }

    public function purge(): void
    {
        // Because MySQL rocks, you got to disable foreign key checks when doing a TRUNCATE/DELETE unlike in for example
        // PostgreSQL. This ideally should be done in the Purger of doctrine/data-fixtures but meanwhile we are doing
        // it here.
        // See the progress in https://github.com/doctrine/data-fixtures/pull/272
        $disableFkChecks = (
            $this->purger instanceof DoctrineOrmPurger
            && in_array($this->purgeMode->getValue(), [PurgeMode::createDeleteMode()->getValue(), PurgeMode::createTruncateMode()->getValue()])
            && $this->purger->getObjectManager()->getConnection()->getDatabasePlatform() instanceof MySqlPlatform
        );

        if ($disableFkChecks) {
            $connection = $this->purger->getObjectManager()->getConnection();

            $connection->executeStatement('SET FOREIGN_KEY_CHECKS = 0;');
        }

        $connection->executeStatement('TRUNCATE TABLE country_version;');
        $connection->executeStatement('TRUNCATE TABLE external_lookup_version;');
        $connection->executeStatement('TRUNCATE TABLE ocha_presence_external_id_version;');
        $connection->executeStatement('TRUNCATE TABLE ocha_presence_version;');

        $this->purger->purge();

        if ($disableFkChecks && isset($connection)) {
            $connection->executeStatement('SET FOREIGN_KEY_CHECKS = 1;');
        }
    }

    private static function createPurger(ObjectManager $manager, ?PurgeMode $purgeMode): DoctrinePurgerInterface
    {
        if ($manager instanceof EntityManagerInterface) {
            $purger = new DoctrineOrmPurger($manager);

            if (null !== $purgeMode) {
                $purger->setPurgeMode($purgeMode->getValue());
            }

            return $purger;
        }

        if ($manager instanceof DoctrinePhpCrDocumentManager) {
            return new DoctrinePhpCrPurger($manager);
        }

        if ($manager instanceof DoctrineMongoDocumentManager) {
            return new DoctrineMongoDBPurger($manager);
        }

        throw new InvalidArgumentException(
            sprintf(
                'Cannot create a purger for ObjectManager of class %s',
                get_class($manager)
            )
        );
    }

}
