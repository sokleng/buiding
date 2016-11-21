<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160722032148 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE project_unit_project_contact DROP CONSTRAINT fk_71e7d04aff075e78');
        $this->addSql('ALTER TABLE project_booking DROP CONSTRAINT fk_7612e1bbe7a1254a');
        $this->addSql('ALTER TABLE project_payment DROP CONSTRAINT fk_fb368868e7a1254a');
        $this->addSql('CREATE TABLE project_unit_company_contact (project_unit_id INT NOT NULL, company_contact_id INT NOT NULL, PRIMARY KEY(project_unit_id, company_contact_id))');
        $this->addSql('CREATE INDEX IDX_C7ABC6F86EA37C68 ON project_unit_company_contact (project_unit_id)');
        $this->addSql('CREATE INDEX IDX_C7ABC6F85A2FCCDC ON project_unit_company_contact (company_contact_id)');
        $this->addSql('ALTER TABLE project_unit_company_contact ADD CONSTRAINT FK_C7ABC6F86EA37C68 FOREIGN KEY (project_unit_id) REFERENCES project_unit (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE project_unit_company_contact ADD CONSTRAINT FK_C7ABC6F85A2FCCDC FOREIGN KEY (company_contact_id) REFERENCES company_contact (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('DROP TABLE project_contact');
        $this->addSql('DROP TABLE project_unit_project_contact');
        $this->addSql('ALTER TABLE project_payment ADD CONSTRAINT FK_FB368868E7A1254A FOREIGN KEY (contact_id) REFERENCES company_contact (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE project_booking ADD CONSTRAINT FK_7612E1BBE7A1254A FOREIGN KEY (contact_id) REFERENCES company_contact (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('CREATE TABLE project_contact (id INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE project_unit_project_contact (project_unit_id INT NOT NULL, project_contact_id INT NOT NULL, PRIMARY KEY(project_unit_id, project_contact_id))');
        $this->addSql('CREATE INDEX idx_71e7d04aff075e78 ON project_unit_project_contact (project_contact_id)');
        $this->addSql('CREATE INDEX idx_71e7d04a6ea37c68 ON project_unit_project_contact (project_unit_id)');
        $this->addSql('ALTER TABLE project_contact ADD CONSTRAINT fk_da7cea5dbf396750 FOREIGN KEY (id) REFERENCES contact (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE project_unit_project_contact ADD CONSTRAINT fk_71e7d04a6ea37c68 FOREIGN KEY (project_unit_id) REFERENCES project_unit (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE project_unit_project_contact ADD CONSTRAINT fk_71e7d04aff075e78 FOREIGN KEY (project_contact_id) REFERENCES project_contact (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('DROP TABLE project_unit_company_contact');
        $this->addSql('ALTER TABLE project_booking DROP CONSTRAINT fk_7612e1bbe7a1254a');
        $this->addSql('ALTER TABLE project_booking ADD CONSTRAINT fk_7612e1bbe7a1254a FOREIGN KEY (contact_id) REFERENCES project_contact (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE project_payment DROP CONSTRAINT fk_fb368868e7a1254a');
        $this->addSql('ALTER TABLE project_payment ADD CONSTRAINT fk_fb368868e7a1254a FOREIGN KEY (contact_id) REFERENCES project_contact (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }
}
