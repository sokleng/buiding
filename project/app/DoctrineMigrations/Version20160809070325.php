<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160809070325 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('DELETE FROM condo_project_listing_profile');
        $this->addSql('DELETE FROM condo_listing_profile');
        $this->addSql('DELETE FROM project_unit_type');
        $this->addSql('DELETE FROM condominium_project');
        $this->addSql('DELETE FROM database_file');
        $this->addSql('ALTER TABLE database_file ADD name VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE database_file ADD description VARCHAR(255) DEFAULT NULL');
         $this->addSql('ALTER TABLE database_file ADD contact_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE database_file ADD CONSTRAINT FK_FEE2745CE7A1254A FOREIGN KEY (contact_id) REFERENCES company_contact (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_FEE2745CE7A1254A ON database_file (contact_id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE database_file DROP name');
        $this->addSql('ALTER TABLE database_file DROP description');
    }
}
