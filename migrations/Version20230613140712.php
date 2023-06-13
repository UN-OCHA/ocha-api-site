<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230613140712 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE country (id VARCHAR(3) NOT NULL, ocha_presence_id VARCHAR(10) DEFAULT NULL, name VARCHAR(255) NOT NULL, iso2 VARCHAR(2) NOT NULL, iso3 VARCHAR(3) NOT NULL, code VARCHAR(10) NOT NULL, INDEX IDX_5373C966B2F297E3 (ocha_presence_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE external_lookup (id VARCHAR(255) NOT NULL, provider VARCHAR(255) NOT NULL, year VARCHAR(4) NOT NULL, iso3 VARCHAR(3) NULL, external_id VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE key_figures (id VARCHAR(255) NOT NULL, iso3 VARCHAR(3) NOT NULL, country VARCHAR(255) NOT NULL, year VARCHAR(4) NOT NULL, name VARCHAR(255) NOT NULL, value NUMERIC(20, 2) NOT NULL, updated DATETIME DEFAULT NULL, url LONGTEXT DEFAULT NULL, source VARCHAR(255) DEFAULT NULL, description LONGTEXT DEFAULT NULL, tags JSON DEFAULT NULL, provider VARCHAR(255) NOT NULL, extra JSON DEFAULT NULL, archived TINYINT(1) DEFAULT NULL, value_string VARCHAR(255) DEFAULT NULL, value_type VARCHAR(255) DEFAULT NULL, unit VARCHAR(255) DEFAULT NULL, figure_id VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE n8n_category (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE n8n_collection (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, nodes JSON DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE n8n_collection_n8n_workflow (n8n_collection_id INT NOT NULL, n8n_workflow_id INT NOT NULL, INDEX IDX_5E8A5514F9CAB60B (n8n_collection_id), INDEX IDX_5E8A55141D108A36 (n8n_workflow_id), PRIMARY KEY(n8n_collection_id, n8n_workflow_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE n8n_workflow (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, workflow JSON DEFAULT NULL, user JSON DEFAULT NULL, nodes JSON DEFAULT NULL, created_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE n8n_workflow_n8n_category (n8n_workflow_id INT NOT NULL, n8n_category_id INT NOT NULL, INDEX IDX_839065D1D108A36 (n8n_workflow_id), INDEX IDX_839065D232A3B6E (n8n_category_id), PRIMARY KEY(n8n_workflow_id, n8n_category_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ocha_presence (id VARCHAR(10) NOT NULL, name VARCHAR(255) NOT NULL, office_type VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ocha_presence_external_id (id INT AUTO_INCREMENT NOT NULL, ocha_presence_id VARCHAR(10) NOT NULL, provider_id VARCHAR(255) NOT NULL, year VARCHAR(4) NOT NULL, INDEX IDX_6373E70DB2F297E3 (ocha_presence_id), INDEX IDX_6373E70DA53A8AA (provider_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ocha_presence_external_id_external_lookup (ocha_presence_external_id_id INT NOT NULL, external_lookup_id VARCHAR(255) NOT NULL, INDEX IDX_42811115B6AA13AD (ocha_presence_external_id_id), INDEX IDX_42811115AD0E6B89 (external_lookup_id), PRIMARY KEY(ocha_presence_external_id_id, external_lookup_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE provider (id VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, prefix VARCHAR(255) DEFAULT NULL, expand VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, username VARCHAR(255) DEFAULT NULL, full_name VARCHAR(255) DEFAULT NULL, token VARCHAR(255) DEFAULT NULL, providers JSON DEFAULT NULL, can_read JSON DEFAULT NULL, can_write JSON DEFAULT NULL, webhook VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE country ADD CONSTRAINT FK_5373C966B2F297E3 FOREIGN KEY (ocha_presence_id) REFERENCES ocha_presence (id)');
        $this->addSql('ALTER TABLE n8n_collection_n8n_workflow ADD CONSTRAINT FK_5E8A5514F9CAB60B FOREIGN KEY (n8n_collection_id) REFERENCES n8n_collection (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE n8n_collection_n8n_workflow ADD CONSTRAINT FK_5E8A55141D108A36 FOREIGN KEY (n8n_workflow_id) REFERENCES n8n_workflow (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE n8n_workflow_n8n_category ADD CONSTRAINT FK_839065D1D108A36 FOREIGN KEY (n8n_workflow_id) REFERENCES n8n_workflow (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE n8n_workflow_n8n_category ADD CONSTRAINT FK_839065D232A3B6E FOREIGN KEY (n8n_category_id) REFERENCES n8n_category (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE ocha_presence_external_id ADD CONSTRAINT FK_6373E70DB2F297E3 FOREIGN KEY (ocha_presence_id) REFERENCES ocha_presence (id)');
        $this->addSql('ALTER TABLE ocha_presence_external_id ADD CONSTRAINT FK_6373E70DA53A8AA FOREIGN KEY (provider_id) REFERENCES provider (id)');
        $this->addSql('ALTER TABLE ocha_presence_external_id_external_lookup ADD CONSTRAINT FK_42811115B6AA13AD FOREIGN KEY (ocha_presence_external_id_id) REFERENCES ocha_presence_external_id (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE ocha_presence_external_id_external_lookup ADD CONSTRAINT FK_42811115AD0E6B89 FOREIGN KEY (external_lookup_id) REFERENCES external_lookup (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE country DROP FOREIGN KEY FK_5373C966B2F297E3');
        $this->addSql('ALTER TABLE n8n_collection_n8n_workflow DROP FOREIGN KEY FK_5E8A5514F9CAB60B');
        $this->addSql('ALTER TABLE n8n_collection_n8n_workflow DROP FOREIGN KEY FK_5E8A55141D108A36');
        $this->addSql('ALTER TABLE n8n_workflow_n8n_category DROP FOREIGN KEY FK_839065D1D108A36');
        $this->addSql('ALTER TABLE n8n_workflow_n8n_category DROP FOREIGN KEY FK_839065D232A3B6E');
        $this->addSql('ALTER TABLE ocha_presence_external_id DROP FOREIGN KEY FK_6373E70DB2F297E3');
        $this->addSql('ALTER TABLE ocha_presence_external_id DROP FOREIGN KEY FK_6373E70DA53A8AA');
        $this->addSql('ALTER TABLE ocha_presence_external_id_external_lookup DROP FOREIGN KEY FK_42811115B6AA13AD');
        $this->addSql('ALTER TABLE ocha_presence_external_id_external_lookup DROP FOREIGN KEY FK_42811115AD0E6B89');
        $this->addSql('DROP TABLE country');
        $this->addSql('DROP TABLE external_lookup');
        $this->addSql('DROP TABLE key_figures');
        $this->addSql('DROP TABLE n8n_category');
        $this->addSql('DROP TABLE n8n_collection');
        $this->addSql('DROP TABLE n8n_collection_n8n_workflow');
        $this->addSql('DROP TABLE n8n_workflow');
        $this->addSql('DROP TABLE n8n_workflow_n8n_category');
        $this->addSql('DROP TABLE ocha_presence');
        $this->addSql('DROP TABLE ocha_presence_external_id');
        $this->addSql('DROP TABLE ocha_presence_external_id_external_lookup');
        $this->addSql('DROP TABLE provider');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
