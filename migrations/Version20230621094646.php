<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230621094646 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE ocha_presence_country (ocha_presence_id VARCHAR(10) NOT NULL, country_id VARCHAR(3) NOT NULL, INDEX IDX_AA26AAE9B2F297E3 (ocha_presence_id), INDEX IDX_AA26AAE9F92F3E70 (country_id), PRIMARY KEY(ocha_presence_id, country_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE ocha_presence_country ADD CONSTRAINT FK_AA26AAE9B2F297E3 FOREIGN KEY (ocha_presence_id) REFERENCES ocha_presence (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE ocha_presence_country ADD CONSTRAINT FK_AA26AAE9F92F3E70 FOREIGN KEY (country_id) REFERENCES country (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE ocha_presence_country DROP FOREIGN KEY FK_AA26AAE9B2F297E3');
        $this->addSql('ALTER TABLE ocha_presence_country DROP FOREIGN KEY FK_AA26AAE9F92F3E70');
        $this->addSql('DROP TABLE ocha_presence_country');
    }
}
