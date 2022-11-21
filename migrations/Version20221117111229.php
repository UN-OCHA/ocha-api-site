<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221117111229 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE n8n_category (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE n8n_collection (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, nodes JSON DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE n8n_collection_n8n_workflow (n8n_collection_id INT NOT NULL, n8n_workflow_id INT NOT NULL, INDEX IDX_5E8A5514F9CAB60B (n8n_collection_id), INDEX IDX_5E8A55141D108A36 (n8n_workflow_id), PRIMARY KEY(n8n_collection_id, n8n_workflow_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE n8n_workflow (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, workflow JSON DEFAULT NULL, user JSON DEFAULT NULL, nodes JSON DEFAULT NULL, created_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE n8n_workflow_n8n_category (n8n_workflow_id INT NOT NULL, n8n_category_id INT NOT NULL, INDEX IDX_839065D1D108A36 (n8n_workflow_id), INDEX IDX_839065D232A3B6E (n8n_category_id), PRIMARY KEY(n8n_workflow_id, n8n_category_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE n8n_collection_n8n_workflow ADD CONSTRAINT FK_5E8A5514F9CAB60B FOREIGN KEY (n8n_collection_id) REFERENCES n8n_collection (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE n8n_collection_n8n_workflow ADD CONSTRAINT FK_5E8A55141D108A36 FOREIGN KEY (n8n_workflow_id) REFERENCES n8n_workflow (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE n8n_workflow_n8n_category ADD CONSTRAINT FK_839065D1D108A36 FOREIGN KEY (n8n_workflow_id) REFERENCES n8n_workflow (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE n8n_workflow_n8n_category ADD CONSTRAINT FK_839065D232A3B6E FOREIGN KEY (n8n_category_id) REFERENCES n8n_category (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE n8n_collection_n8n_workflow DROP FOREIGN KEY FK_5E8A5514F9CAB60B');
        $this->addSql('ALTER TABLE n8n_collection_n8n_workflow DROP FOREIGN KEY FK_5E8A55141D108A36');
        $this->addSql('ALTER TABLE n8n_workflow_n8n_category DROP FOREIGN KEY FK_839065D1D108A36');
        $this->addSql('ALTER TABLE n8n_workflow_n8n_category DROP FOREIGN KEY FK_839065D232A3B6E');
        $this->addSql('DROP TABLE n8n_category');
        $this->addSql('DROP TABLE n8n_collection');
        $this->addSql('DROP TABLE n8n_collection_n8n_workflow');
        $this->addSql('DROP TABLE n8n_workflow');
        $this->addSql('DROP TABLE n8n_workflow_n8n_category');
    }
}
