<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230606084321 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE ocha_presence_external_id (id INT AUTO_INCREMENT NOT NULL, ocha_presence_id VARCHAR(10) NOT NULL, provider_id VARCHAR(255) NOT NULL, year VARCHAR(4) NOT NULL, INDEX IDX_6373E70DB2F297E3 (ocha_presence_id), INDEX IDX_6373E70DA53A8AA (provider_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE ocha_presence_external_id ADD CONSTRAINT FK_6373E70DB2F297E3 FOREIGN KEY (ocha_presence_id) REFERENCES ocha_presence (id)');
        $this->addSql('ALTER TABLE ocha_presence_external_id ADD CONSTRAINT FK_6373E70DA53A8AA FOREIGN KEY (provider_id) REFERENCES provider (id)');
        $this->addSql('ALTER TABLE external_lookup ADD ocha_presence_external_id_id INT NULL');
        $this->addSql('ALTER TABLE external_lookup ADD CONSTRAINT FK_849F2EECB6AA13AD FOREIGN KEY (ocha_presence_external_id_id) REFERENCES ocha_presence_external_id (id)');
        $this->addSql('CREATE INDEX IDX_849F2EECB6AA13AD ON external_lookup (ocha_presence_external_id_id)');
        $this->addSql('ALTER TABLE ocha_presence DROP FOREIGN KEY FK_CB77949A53A8AA');
        $this->addSql('DROP INDEX IDX_CB77949A53A8AA ON ocha_presence');
        $this->addSql('ALTER TABLE ocha_presence DROP provider_id, DROP external_ids');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE external_lookup DROP FOREIGN KEY FK_849F2EECB6AA13AD');
        $this->addSql('ALTER TABLE ocha_presence_external_id DROP FOREIGN KEY FK_6373E70DB2F297E3');
        $this->addSql('ALTER TABLE ocha_presence_external_id DROP FOREIGN KEY FK_6373E70DA53A8AA');
        $this->addSql('DROP TABLE ocha_presence_external_id');
        $this->addSql('ALTER TABLE ocha_presence ADD provider_id VARCHAR(255) NOT NULL, ADD external_ids LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\'');
        $this->addSql('ALTER TABLE ocha_presence ADD CONSTRAINT FK_CB77949A53A8AA FOREIGN KEY (provider_id) REFERENCES provider (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_CB77949A53A8AA ON ocha_presence (provider_id)');
        $this->addSql('DROP INDEX IDX_849F2EECB6AA13AD ON external_lookup');
        $this->addSql('ALTER TABLE external_lookup DROP ocha_presence_external_id_id');
    }
}
