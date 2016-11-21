<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160711023240 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SEQUENCE project_payment_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE project_payment (id INT NOT NULL, contact_id INT NOT NULL, project_id INT NOT NULL, receiver VARCHAR(255) NOT NULL, payment_method VARCHAR(255) NOT NULL, creation_date TIMESTAMP(0) WITH TIME ZONE NOT NULL, description TEXT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_FB368868E7A1254A ON project_payment (contact_id)');
        $this->addSql('CREATE INDEX IDX_FB368868166D1F9C ON project_payment (project_id)');
        $this->addSql('ALTER TABLE project_payment ADD CONSTRAINT FK_FB368868E7A1254A FOREIGN KEY (contact_id) REFERENCES project_contact (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE project_payment ADD CONSTRAINT FK_FB368868166D1F9C FOREIGN KEY (project_id) REFERENCES condominium_project (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('DROP SEQUENCE project_payment_id_seq CASCADE');
        $this->addSql('DROP TABLE project_payment');
    }
}
