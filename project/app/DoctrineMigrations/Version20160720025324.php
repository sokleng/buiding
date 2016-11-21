<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160720025324 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('DROP SEQUENCE project_contact_id_seq CASCADE');
        $this->addSql('CREATE SEQUENCE contact_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE contact (id INT NOT NULL, comment TEXT DEFAULT NULL, address VARCHAR(255) DEFAULT NULL, phone_number VARCHAR(255) DEFAULT NULL, id_number VARCHAR(255) DEFAULT NULL, email VARCHAR(255) DEFAULT NULL, nationality VARCHAR(255) DEFAULT NULL, creation_date TIMESTAMP(0) WITH TIME ZONE NOT NULL, name VARCHAR(255) NOT NULL, discr VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE company_contact (id INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE realty_company_company_contact (realty_company_id INT NOT NULL, company_contact_id INT NOT NULL, PRIMARY KEY(realty_company_id, company_contact_id))');
        $this->addSql('CREATE INDEX IDX_786A0D5F3B6B89A2 ON realty_company_company_contact (realty_company_id)');
        $this->addSql('CREATE INDEX IDX_786A0D5F5A2FCCDC ON realty_company_company_contact (company_contact_id)');
        $this->addSql('ALTER TABLE company_contact ADD CONSTRAINT FK_6C30FCEFBF396750 FOREIGN KEY (id) REFERENCES contact (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE realty_company_company_contact ADD CONSTRAINT FK_786A0D5F3B6B89A2 FOREIGN KEY (realty_company_id) REFERENCES realty_company (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE realty_company_company_contact ADD CONSTRAINT FK_786A0D5F5A2FCCDC FOREIGN KEY (company_contact_id) REFERENCES company_contact (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('DELETE FROM project_contact');
        $this->addSql('ALTER TABLE project_contact DROP comment');
        $this->addSql('ALTER TABLE project_contact DROP address');
        $this->addSql('ALTER TABLE project_contact DROP phone_number');
        $this->addSql('ALTER TABLE project_contact DROP creation_date');
        $this->addSql('ALTER TABLE project_contact DROP name');
        $this->addSql('ALTER TABLE project_contact DROP id_number');
        $this->addSql('ALTER TABLE project_contact DROP email');
        $this->addSql('ALTER TABLE project_contact DROP nationality');
        $this->addSql('ALTER TABLE project_contact ADD CONSTRAINT FK_DA7CEA5DBF396750 FOREIGN KEY (id) REFERENCES contact (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE project_contact DROP CONSTRAINT FK_DA7CEA5DBF396750');
        $this->addSql('ALTER TABLE company_contact DROP CONSTRAINT FK_6C30FCEFBF396750');
        $this->addSql('ALTER TABLE realty_company_company_contact DROP CONSTRAINT FK_786A0D5F5A2FCCDC');
        $this->addSql('DROP SEQUENCE contact_id_seq CASCADE');
        $this->addSql('CREATE SEQUENCE project_contact_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('DROP TABLE contact');
        $this->addSql('DROP TABLE company_contact');
        $this->addSql('DROP TABLE realty_company_company_contact');
        $this->addSql('ALTER TABLE project_contact ADD comment TEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE project_contact ADD address VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE project_contact ADD phone_number VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE project_contact ADD creation_date TIMESTAMP(0) WITH TIME ZONE NOT NULL');
        $this->addSql('ALTER TABLE project_contact ADD name VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE project_contact ADD id_number VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE project_contact ADD email VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE project_contact ADD nationality VARCHAR(255) DEFAULT NULL');
    }
}
