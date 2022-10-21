<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221021071310 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE n8n_credential_usage DROP FOREIGN KEY FK_518e1ece107b859ca6ce9ed2487f7e23');
        $this->addSql('ALTER TABLE n8n_credential_usage DROP FOREIGN KEY FK_7ce200a20ade7ae89fa7901da896993f');
        $this->addSql('ALTER TABLE n8n_user DROP FOREIGN KEY FK_f0609be844f9200ff4365b1bb3d');
        $this->addSql('ALTER TABLE n8n_workflows_tags DROP FOREIGN KEY FK_54b2f0343d6a2078fa137443869');
        $this->addSql('ALTER TABLE n8n_workflows_tags DROP FOREIGN KEY FK_77505b341625b0b4768082e2171');
        $this->addSql('DROP TABLE n8n_credential_usage');
        $this->addSql('DROP TABLE n8n_credentials_entity');
        $this->addSql('DROP TABLE n8n_execution_entity');
        $this->addSql('DROP TABLE n8n_installed_nodes');
        $this->addSql('DROP TABLE n8n_installed_packages');
        $this->addSql('DROP TABLE n8n_migrations');
        $this->addSql('DROP TABLE n8n_role');
        $this->addSql('DROP TABLE n8n_settings');
        $this->addSql('DROP TABLE n8n_shared_credentials');
        $this->addSql('DROP TABLE n8n_shared_workflow');
        $this->addSql('DROP TABLE n8n_tag_entity');
        $this->addSql('DROP TABLE n8n_user');
        $this->addSql('DROP TABLE n8n_webhook_entity');
        $this->addSql('DROP TABLE n8n_workflow_entity');
        $this->addSql('DROP TABLE n8n_workflows_tags');
        $this->addSql('ALTER TABLE user ADD token VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE n8n_credential_usage (workflowId INT NOT NULL, nodeId CHAR(200) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_0900_ai_ci`, credentialId INT DEFAULT 1 NOT NULL, createdAt DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, updatedAt DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, INDEX FK_7ce200a20ade7ae89fa7901da896993f (credentialId), INDEX IDX_92D47EDC8F18896 (workflowId), PRIMARY KEY(workflowId, nodeId, credentialId)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_0900_ai_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE n8n_credentials_entity (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(128) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_0900_ai_ci`, data TEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_0900_ai_ci`, type VARCHAR(128) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_0900_ai_ci`, nodesAccess LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_bin`, createdAt DATETIME DEFAULT \'CURRENT_TIMESTAMP(3)\' NOT NULL, updatedAt DATETIME DEFAULT \'CURRENT_TIMESTAMP(3)\' NOT NULL, INDEX IDX_07fde106c0b471d8cc80a64fc8 (type), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_0900_ai_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE n8n_execution_entity (id INT AUTO_INCREMENT NOT NULL, data MEDIUMTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_0900_ai_ci`, finished TINYINT(1) NOT NULL, mode VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_0900_ai_ci`, retryOf VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_0900_ai_ci`, retrySuccessId VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_0900_ai_ci`, startedAt DATETIME NOT NULL, stoppedAt DATETIME DEFAULT NULL, workflowData LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_bin`, workflowId VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_0900_ai_ci`, waitTill DATETIME DEFAULT NULL, INDEX IDX_06da892aaf92a48e7d3e400003 (workflowId, waitTill, id), INDEX IDX_1688846335d274033e15c846a4 (finished, id), INDEX IDX_78d62b89dc1433192b86dce18a (workflowId, finished, id), INDEX IDX_b94b45ce2c73ce46c54f20b5f9 (waitTill, id), INDEX IDX_cefb067df2402f6aed0638a6c1 (stoppedAt), INDEX IDX_81fc04c8a17de15835713505e4 (workflowId, id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_0900_ai_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE n8n_installed_nodes (name CHAR(200) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_0900_ai_ci`, type CHAR(200) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_0900_ai_ci`, latestVersion INT DEFAULT 1 NOT NULL, package CHAR(214) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_0900_ai_ci`, INDEX FK_73f857fc5dce682cef8a99c11dbddbc969618951 (package), PRIMARY KEY(name)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_0900_ai_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE n8n_installed_packages (packageName CHAR(214) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_0900_ai_ci`, installedVersion CHAR(50) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_0900_ai_ci`, authorName CHAR(70) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_0900_ai_ci`, authorEmail CHAR(70) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_0900_ai_ci`, createdAt DATETIME DEFAULT CURRENT_TIMESTAMP, updatedAt DATETIME DEFAULT CURRENT_TIMESTAMP, PRIMARY KEY(packageName)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_0900_ai_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE n8n_migrations (id INT AUTO_INCREMENT NOT NULL, timestamp BIGINT NOT NULL, name VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_0900_ai_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_0900_ai_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE n8n_role (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(32) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_0900_ai_ci`, scope VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_0900_ai_ci`, createdAt DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, updatedAt DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, UNIQUE INDEX UQ_5b49d0f504f7ef31045a1fb2eb8 (scope, name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_0900_ai_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE n8n_settings (`key` VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_0900_ai_ci`, value TEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_0900_ai_ci`, loadOnStartup TINYINT(1) DEFAULT 0 NOT NULL, PRIMARY KEY(`key`)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_0900_ai_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE n8n_shared_credentials (createdAt DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, updatedAt DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, roleId INT NOT NULL, userId VARCHAR(36) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_0900_ai_ci`, credentialsId INT NOT NULL, INDEX FK_484f0327e778648dd04f1d70493 (userId), INDEX FK_68661def1d4bcf2451ac8dbd949 (credentialsId), INDEX FK_c68e056637562000b68f480815a (roleId), PRIMARY KEY(userId, credentialsId)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_0900_ai_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE n8n_shared_workflow (createdAt DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, updatedAt DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, roleId INT NOT NULL, userId VARCHAR(36) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_0900_ai_ci`, workflowId INT NOT NULL, INDEX FK_3540da03964527aa24ae014b780x (roleId), INDEX FK_82b2fd9ec4e3e24209af8160282x (userId), INDEX FK_b83f8d2530884b66a9c848c8b88x (workflowId), PRIMARY KEY(userId, workflowId)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_0900_ai_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE n8n_tag_entity (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(24) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_0900_ai_ci`, createdAt DATETIME DEFAULT \'CURRENT_TIMESTAMP(3)\' NOT NULL, updatedAt DATETIME DEFAULT \'CURRENT_TIMESTAMP(3)\' NOT NULL, UNIQUE INDEX IDX_8f949d7a3a984759044054e89b (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_0900_ai_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE n8n_user (id VARCHAR(36) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_0900_ai_ci`, email VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_0900_ai_ci`, firstName VARCHAR(32) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_0900_ai_ci`, lastName VARCHAR(32) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_0900_ai_ci`, password VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_0900_ai_ci`, resetPasswordToken VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_0900_ai_ci`, resetPasswordTokenExpiration INT DEFAULT NULL, personalizationAnswers LONGTEXT CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_bin`, createdAt DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, updatedAt DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, globalRoleId INT NOT NULL, settings LONGTEXT CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_bin`, apiKey VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_0900_ai_ci`, UNIQUE INDEX IDX_e12875dfb3b1d92d7d7c5377e2 (email), INDEX FK_f0609be844f9200ff4365b1bb3d (globalRoleId), UNIQUE INDEX UQ_ie0zomxves9w3p774drfrkxtj5 (apiKey), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_0900_ai_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE n8n_webhook_entity (method VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_0900_ai_ci`, workflowId INT NOT NULL, webhookPath VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_0900_ai_ci`, node VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_0900_ai_ci`, webhookId VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_0900_ai_ci`, pathLength INT DEFAULT NULL, INDEX IDX_742496f199721a057051acf4c2 (webhookId, method, pathLength), PRIMARY KEY(webhookPath, method)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_0900_ai_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE n8n_workflow_entity (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(128) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_0900_ai_ci`, active TINYINT(1) NOT NULL, nodes LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_bin`, connections LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_bin`, createdAt DATETIME DEFAULT \'CURRENT_TIMESTAMP(3)\' NOT NULL, updatedAt DATETIME DEFAULT \'CURRENT_TIMESTAMP(3)\' NOT NULL, settings LONGTEXT CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_bin`, staticData LONGTEXT CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_bin`, pinData LONGTEXT CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_bin`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_0900_ai_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE n8n_workflows_tags (workflowId INT NOT NULL, tagId INT NOT NULL, INDEX IDX_54b2f0343d6a2078fa13744386 (workflowId), INDEX IDX_77505b341625b0b4768082e217 (tagId), PRIMARY KEY(workflowId, tagId)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_0900_ai_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE n8n_credential_usage ADD CONSTRAINT FK_518e1ece107b859ca6ce9ed2487f7e23 FOREIGN KEY (workflowId) REFERENCES n8n_workflow_entity (id) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE n8n_credential_usage ADD CONSTRAINT FK_7ce200a20ade7ae89fa7901da896993f FOREIGN KEY (credentialId) REFERENCES n8n_credentials_entity (id) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE n8n_user ADD CONSTRAINT FK_f0609be844f9200ff4365b1bb3d FOREIGN KEY (globalRoleId) REFERENCES n8n_role (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE n8n_workflows_tags ADD CONSTRAINT FK_54b2f0343d6a2078fa137443869 FOREIGN KEY (workflowId) REFERENCES n8n_workflow_entity (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE n8n_workflows_tags ADD CONSTRAINT FK_77505b341625b0b4768082e2171 FOREIGN KEY (tagId) REFERENCES n8n_tag_entity (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user DROP token');
    }
}
