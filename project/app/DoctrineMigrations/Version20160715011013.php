<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160715011013 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE condominium_project DROP CONSTRAINT fk_114faf9e3248cfe6');
        $this->addSql('DROP INDEX idx_114faf9e3248cfe6');
        $this->addSql('ALTER TABLE condominium_project RENAME COLUMN realty_comapny_id TO realty_company_id');
        $this->addSql('ALTER TABLE condominium_project ADD CONSTRAINT FK_114FAF9E3B6B89A2 FOREIGN KEY (realty_company_id) REFERENCES realty_company (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_114FAF9E3B6B89A2 ON condominium_project (realty_company_id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE condominium_project DROP CONSTRAINT FK_114FAF9E3B6B89A2');
        $this->addSql('DROP INDEX IDX_114FAF9E3B6B89A2');
        $this->addSql('ALTER TABLE condominium_project RENAME COLUMN realty_company_id TO realty_comapny_id');
        $this->addSql('ALTER TABLE condominium_project ADD CONSTRAINT fk_114faf9e3248cfe6 FOREIGN KEY (realty_comapny_id) REFERENCES realty_company (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_114faf9e3248cfe6 ON condominium_project (realty_comapny_id)');
    }
}
