<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230920112505 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE hdx_admin1 (id INT AUTO_INCREMENT NOT NULL, location_ref_id INT DEFAULT NULL, code VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, centroid_lat NUMERIC(10, 7) DEFAULT NULL, centroid_lon NUMERIC(10, 7) DEFAULT NULL, is_historical TINYINT(1) DEFAULT NULL, valid_date DATE DEFAULT NULL, INDEX IDX_1ED7B316AC5C9AB (location_ref_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE hdx_admin2 (id INT AUTO_INCREMENT NOT NULL, admin1_ref_id INT DEFAULT NULL, code VARCHAR(255) DEFAULT NULL, name VARCHAR(255) NOT NULL, centroid_lat NUMERIC(10, 7) DEFAULT NULL, centroid_lon NUMERIC(10, 7) DEFAULT NULL, is_historical TINYINT(1) DEFAULT NULL, valid_date DATE DEFAULT NULL, INDEX IDX_98E42A8BD9D9962A (admin1_ref_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE hdx_age_range (id INT AUTO_INCREMENT NOT NULL, code VARCHAR(255) NOT NULL, description VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE hdx_dataset (id INT AUTO_INCREMENT NOT NULL, hdx_link VARCHAR(255) DEFAULT NULL, code VARCHAR(255) NOT NULL, title VARCHAR(255) NOT NULL, provider_code VARCHAR(255) NOT NULL, provider_name VARCHAR(255) NOT NULL, api_link VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE hdx_gender (id INT AUTO_INCREMENT NOT NULL, code VARCHAR(1) NOT NULL, description VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE hdx_location (id INT AUTO_INCREMENT NOT NULL, code VARCHAR(3) NOT NULL, name VARCHAR(255) NOT NULL, centroid_lat NUMERIC(10, 7) DEFAULT NULL, centroid_lon NUMERIC(10, 7) DEFAULT NULL, is_historical TINYINT(1) DEFAULT NULL, valid_date DATE DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE hdx_operational_presence (id INT AUTO_INCREMENT NOT NULL, resource_ref_id INT DEFAULT NULL, org_ref_id INT DEFAULT NULL, sector_ref_id INT DEFAULT NULL, admin2_ref_id INT DEFAULT NULL, valid_date DATE DEFAULT NULL, orig_data JSON, INDEX IDX_BD71C5B5E94BAB53 (resource_ref_id), INDEX IDX_BD71C5B582F50C2E (org_ref_id), INDEX IDX_BD71C5B5A66839C7 (sector_ref_id), INDEX IDX_BD71C5B5575691C9 (admin2_ref_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE hdx_org (id INT AUTO_INCREMENT NOT NULL, org_type_ref_id INT DEFAULT NULL, hdx_link VARCHAR(255) DEFAULT NULL, acronym VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, valid_date DATE DEFAULT NULL, INDEX IDX_61C847D89EEC7077 (org_type_ref_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE hdx_org_type (id INT AUTO_INCREMENT NOT NULL, code VARCHAR(255) NOT NULL, description VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE hdx_population (id INT AUTO_INCREMENT NOT NULL, resource_ref_id INT DEFAULT NULL, admin2_ref_id INT DEFAULT NULL, gender_ref_id INT DEFAULT NULL, age_range_ref_id INT DEFAULT NULL, valid_date DATE DEFAULT NULL, population INT NOT NULL, orig_data JSON, INDEX IDX_3EB64E86E94BAB53 (resource_ref_id), INDEX IDX_3EB64E86575691C9 (admin2_ref_id), INDEX IDX_3EB64E86ABC2B3FA (gender_ref_id), INDEX IDX_3EB64E86F7CD470C (age_range_ref_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE hdx_resource (id INT AUTO_INCREMENT NOT NULL, dataset_ref_id INT DEFAULT NULL, hdx_link VARCHAR(255) NOT NULL, code VARCHAR(255) NOT NULL, filename VARCHAR(255) NOT NULL, mime_type VARCHAR(255) NOT NULL, update_date DATE NOT NULL, is_hxl TINYINT(1) DEFAULT NULL, api_link VARCHAR(255) DEFAULT NULL, INDEX IDX_2440599747C19513 (dataset_ref_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE hdx_sector (id INT AUTO_INCREMENT NOT NULL, code VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, valid_date DATE DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE hdx_admin1 ADD CONSTRAINT FK_1ED7B316AC5C9AB FOREIGN KEY (location_ref_id) REFERENCES hdx_location (id)');
        $this->addSql('ALTER TABLE hdx_admin2 ADD CONSTRAINT FK_98E42A8BD9D9962A FOREIGN KEY (admin1_ref_id) REFERENCES hdx_admin1 (id)');
        $this->addSql('ALTER TABLE hdx_operational_presence ADD CONSTRAINT FK_BD71C5B5E94BAB53 FOREIGN KEY (resource_ref_id) REFERENCES hdx_resource (id)');
        $this->addSql('ALTER TABLE hdx_operational_presence ADD CONSTRAINT FK_BD71C5B582F50C2E FOREIGN KEY (org_ref_id) REFERENCES hdx_org (id)');
        $this->addSql('ALTER TABLE hdx_operational_presence ADD CONSTRAINT FK_BD71C5B5A66839C7 FOREIGN KEY (sector_ref_id) REFERENCES hdx_sector (id)');
        $this->addSql('ALTER TABLE hdx_operational_presence ADD CONSTRAINT FK_BD71C5B5575691C9 FOREIGN KEY (admin2_ref_id) REFERENCES hdx_admin2 (id)');
        $this->addSql('ALTER TABLE hdx_org ADD CONSTRAINT FK_61C847D89EEC7077 FOREIGN KEY (org_type_ref_id) REFERENCES hdx_org_type (id)');
        $this->addSql('ALTER TABLE hdx_population ADD CONSTRAINT FK_3EB64E86E94BAB53 FOREIGN KEY (resource_ref_id) REFERENCES hdx_resource (id)');
        $this->addSql('ALTER TABLE hdx_population ADD CONSTRAINT FK_3EB64E86575691C9 FOREIGN KEY (admin2_ref_id) REFERENCES hdx_admin2 (id)');
        $this->addSql('ALTER TABLE hdx_population ADD CONSTRAINT FK_3EB64E86ABC2B3FA FOREIGN KEY (gender_ref_id) REFERENCES hdx_gender (id)');
        $this->addSql('ALTER TABLE hdx_population ADD CONSTRAINT FK_3EB64E86F7CD470C FOREIGN KEY (age_range_ref_id) REFERENCES hdx_age_range (id)');
        $this->addSql('ALTER TABLE hdx_resource ADD CONSTRAINT FK_2440599747C19513 FOREIGN KEY (dataset_ref_id) REFERENCES hdx_dataset (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE hdx_admin1 DROP FOREIGN KEY FK_1ED7B316AC5C9AB');
        $this->addSql('ALTER TABLE hdx_admin2 DROP FOREIGN KEY FK_98E42A8BD9D9962A');
        $this->addSql('ALTER TABLE hdx_operational_presence DROP FOREIGN KEY FK_BD71C5B5E94BAB53');
        $this->addSql('ALTER TABLE hdx_operational_presence DROP FOREIGN KEY FK_BD71C5B582F50C2E');
        $this->addSql('ALTER TABLE hdx_operational_presence DROP FOREIGN KEY FK_BD71C5B5A66839C7');
        $this->addSql('ALTER TABLE hdx_operational_presence DROP FOREIGN KEY FK_BD71C5B5575691C9');
        $this->addSql('ALTER TABLE hdx_org DROP FOREIGN KEY FK_61C847D89EEC7077');
        $this->addSql('ALTER TABLE hdx_population DROP FOREIGN KEY FK_3EB64E86E94BAB53');
        $this->addSql('ALTER TABLE hdx_population DROP FOREIGN KEY FK_3EB64E86575691C9');
        $this->addSql('ALTER TABLE hdx_population DROP FOREIGN KEY FK_3EB64E86ABC2B3FA');
        $this->addSql('ALTER TABLE hdx_population DROP FOREIGN KEY FK_3EB64E86F7CD470C');
        $this->addSql('ALTER TABLE hdx_resource DROP FOREIGN KEY FK_2440599747C19513');
        $this->addSql('DROP TABLE hdx_admin1');
        $this->addSql('DROP TABLE hdx_admin2');
        $this->addSql('DROP TABLE hdx_age_range');
        $this->addSql('DROP TABLE hdx_dataset');
        $this->addSql('DROP TABLE hdx_gender');
        $this->addSql('DROP TABLE hdx_location');
        $this->addSql('DROP TABLE hdx_operational_presence');
        $this->addSql('DROP TABLE hdx_org');
        $this->addSql('DROP TABLE hdx_org_type');
        $this->addSql('DROP TABLE hdx_population');
        $this->addSql('DROP TABLE hdx_resource');
        $this->addSql('DROP TABLE hdx_sector');
    }
}
