<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230605134540 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE ocha_presence ADD provider_id VARCHAR(255) NOT NULL, ADD external_ids LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\'');
        $this->addSql('ALTER TABLE ocha_presence ADD CONSTRAINT FK_CB77949A53A8AA FOREIGN KEY (provider_id) REFERENCES provider (id)');
        $this->addSql('CREATE INDEX IDX_CB77949A53A8AA ON ocha_presence (provider_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE ocha_presence DROP FOREIGN KEY FK_CB77949A53A8AA');
        $this->addSql('DROP INDEX IDX_CB77949A53A8AA ON ocha_presence');
        $this->addSql('ALTER TABLE ocha_presence DROP provider_id, DROP external_ids');
    }
}
