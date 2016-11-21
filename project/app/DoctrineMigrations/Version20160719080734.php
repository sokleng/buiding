<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160719080734 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE condominium_project ADD developer_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE condominium_project ADD CONSTRAINT FK_114FAF9E64DD9267 FOREIGN KEY (developer_id) REFERENCES developer (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_114FAF9E64DD9267 ON condominium_project (developer_id)');
        $this->addSql('TRUNCATE TABLE condominium_project CASCADE');
        $this->addSql('ALTER TABLE condominium_project ALTER developer_id SET NOT NULL');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE condominium_project DROP CONSTRAINT FK_114FAF9E64DD9267');
        $this->addSql('DROP INDEX IDX_114FAF9E64DD9267');
        $this->addSql('ALTER TABLE condominium_project DROP developer_id');
        $this->addSql('ALTER TABLE condominium_project ALTER developer_id DROP NOT NULL');
    }
}
