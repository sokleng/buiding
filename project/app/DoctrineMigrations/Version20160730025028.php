<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160730025028 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('DELETE FROM condo_project_listing_profile');
        $this->addSql('ALTER TABLE condo_project_listing_profile ADD project_id INT NOT NULL');
        $this->addSql('ALTER TABLE condo_project_listing_profile ADD CONSTRAINT FK_DA63F0BC166D1F9C FOREIGN KEY (project_id) REFERENCES condominium_project (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_DA63F0BC166D1F9C ON condo_project_listing_profile (project_id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE condo_project_listing_profile DROP CONSTRAINT FK_DA63F0BC166D1F9C');
        $this->addSql('DROP INDEX UNIQ_DA63F0BC166D1F9C');
        $this->addSql('ALTER TABLE condo_project_listing_profile DROP project_id');
    }
}
