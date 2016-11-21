<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160714074400 extends AbstractMigration
{
   /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SEQUENCE realty_company_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE realty_company (id INT NOT NULL, name VARCHAR(255) NOT NULL, description TEXT NOT NULL, creation_date TIMESTAMP(0) WITH TIME ZONE NOT NULL, contact_number VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE realty_company_user (realty_company_id INT NOT NULL, user_id INT NOT NULL, PRIMARY KEY(realty_company_id, user_id))');
        $this->addSql('CREATE INDEX IDX_ECAB1E5B3B6B89A2 ON realty_company_user (realty_company_id)');
        $this->addSql('CREATE INDEX IDX_ECAB1E5BA76ED395 ON realty_company_user (user_id)');
        $this->addSql('ALTER TABLE realty_company_user ADD CONSTRAINT FK_ECAB1E5B3B6B89A2 FOREIGN KEY (realty_company_id) REFERENCES realty_company (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE realty_company_user ADD CONSTRAINT FK_ECAB1E5BA76ED395 FOREIGN KEY (user_id) REFERENCES fos_user (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE condominium_project ADD realty_comapny_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE condominium_project DROP contact_name');
        $this->addSql('ALTER TABLE condominium_project ADD CONSTRAINT FK_114FAF9E3248CFE6 FOREIGN KEY (realty_comapny_id) REFERENCES realty_company (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_114FAF9E3248CFE6 ON condominium_project (realty_comapny_id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE condominium_project DROP CONSTRAINT FK_114FAF9E3248CFE6');
        $this->addSql('ALTER TABLE realty_company_user DROP CONSTRAINT FK_ECAB1E5B3B6B89A2');
        $this->addSql('DROP SEQUENCE realty_company_id_seq CASCADE');
        $this->addSql('DROP TABLE realty_company');
        $this->addSql('DROP TABLE realty_company_user');
        $this->addSql('DROP INDEX IDX_114FAF9E3248CFE6');
        $this->addSql('ALTER TABLE condominium_project ADD contact_name VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE condominium_project DROP realty_comapny_id');
    }
}
