<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160729035501 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SEQUENCE condo_listing_profile_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE condo_listing_profile (id INT NOT NULL, database_file_id INT DEFAULT NULL, published BOOLEAN NOT NULL, latitude DOUBLE PRECISION NOT NULL, longitude DOUBLE PRECISION NOT NULL, description TEXT NOT NULL, discr VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_A35425A36B02DBC ON condo_listing_profile (database_file_id)');
        $this->addSql('CREATE TABLE condo_project_listing_profile (id INT NOT NULL, construction_date TIMESTAMP(0) WITH TIME ZONE NOT NULL, completion_date TIMESTAMP(0) WITH TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('ALTER TABLE condo_listing_profile ADD CONSTRAINT FK_A35425A36B02DBC FOREIGN KEY (database_file_id) REFERENCES database_file (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE condo_project_listing_profile ADD CONSTRAINT FK_DA63F0BCBF396750 FOREIGN KEY (id) REFERENCES condo_listing_profile (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE condo_project_listing_profile DROP CONSTRAINT FK_DA63F0BCBF396750');
        $this->addSql('DROP SEQUENCE condo_listing_profile_id_seq CASCADE');
        $this->addSql('DROP TABLE condo_listing_profile');
        $this->addSql('DROP TABLE condo_project_listing_profile');
    }
}
