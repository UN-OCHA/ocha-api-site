<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221024170751 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE key_figures (id VARCHAR(255) NOT NULL, iso3 VARCHAR(3) NOT NULL, country VARCHAR(255) NOT NULL, year VARCHAR(4) NOT NULL, name VARCHAR(255) NOT NULL, value NUMERIC(20, 2) NOT NULL, updated DATETIME DEFAULT NULL, url LONGTEXT DEFAULT NULL, source VARCHAR(255) DEFAULT NULL, description LONGTEXT DEFAULT NULL, tags LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:array)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE key_figures');
    }
}
