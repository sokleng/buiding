<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160714092053 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SEQUENCE developer_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE developer (id INT NOT NULL, name VARCHAR(255) NOT NULL, description TEXT NOT NULL, creation_date TIMESTAMP(0) WITH TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE developer_user (developer_id INT NOT NULL, user_id INT NOT NULL, PRIMARY KEY(developer_id, user_id))');
        $this->addSql('CREATE INDEX IDX_D043AB0A64DD9267 ON developer_user (developer_id)');
        $this->addSql('CREATE INDEX IDX_D043AB0AA76ED395 ON developer_user (user_id)');
        $this->addSql('ALTER TABLE developer_user ADD CONSTRAINT FK_D043AB0A64DD9267 FOREIGN KEY (developer_id) REFERENCES developer (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE developer_user ADD CONSTRAINT FK_D043AB0AA76ED395 FOREIGN KEY (user_id) REFERENCES fos_user (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE realty_company ADD developer_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE realty_company ADD CONSTRAINT FK_DD2824CD64DD9267 FOREIGN KEY (developer_id) REFERENCES developer (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_DD2824CD64DD9267 ON realty_company (developer_id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE developer_user DROP CONSTRAINT FK_D043AB0A64DD9267');
        $this->addSql('ALTER TABLE realty_company DROP CONSTRAINT FK_DD2824CD64DD9267');
        $this->addSql('DROP SEQUENCE developer_id_seq CASCADE');
        $this->addSql('DROP TABLE developer');
        $this->addSql('DROP TABLE developer_user');
        $this->addSql('DROP INDEX IDX_DD2824CD64DD9267');
        $this->addSql('ALTER TABLE realty_company DROP developer_id');
    }
}
