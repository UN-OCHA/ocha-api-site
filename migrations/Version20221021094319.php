<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221021094319 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE internally_displaced_persons (id INT AUTO_INCREMENT NOT NULL, iso3 VARCHAR(3) NOT NULL, country VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE internally_displaced_persons_values (id INT AUTO_INCREMENT NOT NULL, parent_id INT DEFAULT NULL, year INT NOT NULL, conflict_stock_displacement NUMERIC(20, 0) DEFAULT NULL, conflict_internal_displacements NUMERIC(20, 0) DEFAULT NULL, disaster_internal_displacements NUMERIC(20, 0) DEFAULT NULL, disaster_stock_displacement NUMERIC(20, 0) DEFAULT NULL, INDEX IDX_8174CE4C727ACA70 (parent_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE internally_displaced_persons_values ADD CONSTRAINT FK_8174CE4C727ACA70 FOREIGN KEY (parent_id) REFERENCES internally_displaced_persons (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE internally_displaced_persons_values DROP FOREIGN KEY FK_8174CE4C727ACA70');
        $this->addSql('DROP TABLE internally_displaced_persons');
        $this->addSql('DROP TABLE internally_displaced_persons_values');
    }
}
