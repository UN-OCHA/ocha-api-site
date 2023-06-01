<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230601082526 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE ocha_presence (id VARCHAR(10) NOT NULL, name VARCHAR(255) NOT NULL, office_type VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE country ADD ocha_presence_id VARCHAR(10) DEFAULT NULL');
        $this->addSql('ALTER TABLE country ADD CONSTRAINT FK_5373C966B2F297E3 FOREIGN KEY (ocha_presence_id) REFERENCES ocha_presence (id)');
        $this->addSql('CREATE INDEX IDX_5373C966B2F297E3 ON country (ocha_presence_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE country DROP FOREIGN KEY FK_5373C966B2F297E3');
        $this->addSql('DROP TABLE ocha_presence');
        $this->addSql('DROP INDEX IDX_5373C966B2F297E3 ON country');
        $this->addSql('ALTER TABLE country DROP ocha_presence_id');
    }
}
