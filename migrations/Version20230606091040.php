<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230606091040 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE ocha_presence_external_id_external_lookup (ocha_presence_external_id_id INT NOT NULL, external_lookup_id VARCHAR(255) NOT NULL, INDEX IDX_42811115B6AA13AD (ocha_presence_external_id_id), INDEX IDX_42811115AD0E6B89 (external_lookup_id), PRIMARY KEY(ocha_presence_external_id_id, external_lookup_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE ocha_presence_external_id_external_lookup ADD CONSTRAINT FK_42811115B6AA13AD FOREIGN KEY (ocha_presence_external_id_id) REFERENCES ocha_presence_external_id (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE ocha_presence_external_id_external_lookup ADD CONSTRAINT FK_42811115AD0E6B89 FOREIGN KEY (external_lookup_id) REFERENCES external_lookup (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE ocha_presence_external_id_external_lookup DROP FOREIGN KEY FK_42811115B6AA13AD');
        $this->addSql('ALTER TABLE ocha_presence_external_id_external_lookup DROP FOREIGN KEY FK_42811115AD0E6B89');
        $this->addSql('DROP TABLE ocha_presence_external_id_external_lookup');
    }
}
