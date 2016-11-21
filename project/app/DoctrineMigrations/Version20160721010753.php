<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160721010753 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('DROP TABLE condominium_project_project_contact');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE TABLE condominium_project_project_contact (condominium_project_id INT NOT NULL, project_contact_id INT NOT NULL, PRIMARY KEY(condominium_project_id, project_contact_id))');
        $this->addSql('CREATE INDEX idx_d18919905e30acfc ON condominium_project_project_contact (condominium_project_id)');
        $this->addSql('CREATE INDEX idx_d1891990ff075e78 ON condominium_project_project_contact (project_contact_id)');
        $this->addSql('ALTER TABLE condominium_project_project_contact ADD CONSTRAINT fk_d18919905e30acfc FOREIGN KEY (condominium_project_id) REFERENCES condominium_project (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE condominium_project_project_contact ADD CONSTRAINT fk_d1891990ff075e78 FOREIGN KEY (project_contact_id) REFERENCES project_contact (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }
}
