<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230606090926 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE external_lookup DROP FOREIGN KEY FK_849F2EECB6AA13AD');
        $this->addSql('DROP INDEX FK_849F2EECB6AA13AD ON external_lookup');
        $this->addSql('ALTER TABLE external_lookup DROP ocha_presence_external_id_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE external_lookup ADD ocha_presence_external_id_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE external_lookup ADD CONSTRAINT FK_849F2EECB6AA13AD FOREIGN KEY (ocha_presence_external_id_id) REFERENCES ocha_presence_external_id (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX FK_849F2EECB6AA13AD ON external_lookup (ocha_presence_external_id_id)');
    }
}
