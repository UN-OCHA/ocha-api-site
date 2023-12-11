<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231026105300 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE country ADD version INT DEFAULT 1 NOT NULL');
        $this->addSql('ALTER TABLE external_lookup ADD version INT DEFAULT 1 NOT NULL');
        $this->addSql('ALTER TABLE ocha_presence ADD version INT DEFAULT 1 NOT NULL');
        $this->addSql('ALTER TABLE ocha_presence_external_id ADD version INT DEFAULT 1 NOT NULL');

        $this->addSql('ALTER TABLE ocha_presence_country ADD id INT AUTO_INCREMENT NOT NULL UNIQUE FIRST');
        $this->addSql('ALTER TABLE ocha_presence_external_id_external_lookup ADD id INT AUTO_INCREMENT NOT NULL UNIQUE FIRST');
        $this->addSql('ALTER TABLE ocha_presence_country ADD version INT DEFAULT 1 NOT NULL');
        $this->addSql('ALTER TABLE ocha_presence_external_id_external_lookup ADD version INT DEFAULT 1 NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE country DROP version');
        $this->addSql('ALTER TABLE external_lookup DROP version');
        $this->addSql('ALTER TABLE ocha_presence DROP version');
        $this->addSql('ALTER TABLE ocha_presence_external_id DROP version');

        $this->addSql('ALTER TABLE ocha_presence_country DROP id');
        $this->addSql('ALTER TABLE ocha_presence_external_id_external_lookup DROP id');
        $this->addSql('ALTER TABLE ocha_presence_country DROP version');
        $this->addSql('ALTER TABLE ocha_presence_external_id_external_lookup DROP version');
    }
}
