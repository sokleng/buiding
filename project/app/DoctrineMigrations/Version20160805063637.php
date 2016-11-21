<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160805063637 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');
        $this->addSql('DELETE FROM condo_project_listing_profile');
        $this->addSql('DELETE FROM project_unit_type');
        $this->addSql('DELETE FROM condominium_project');
        $this->addSql('ALTER TABLE condominium_project ADD district_id INT NOT NULL');
        $this->addSql('ALTER TABLE condominium_project ADD CONSTRAINT FK_114FAF9EB08FA272 FOREIGN KEY (district_id) REFERENCES district (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_114FAF9EB08FA272 ON condominium_project (district_id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE condominium_project DROP CONSTRAINT FK_114FAF9EB08FA272');
        $this->addSql('DROP INDEX IDX_114FAF9EB08FA272');
        $this->addSql('ALTER TABLE condominium_project DROP district_id');
    }
}
