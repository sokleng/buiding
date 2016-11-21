<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160706092752 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('DROP INDEX uniq_18b673fe823105');
        $this->addSql('ALTER TABLE unit_type ADD code VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE project_unit_type ADD code VARCHAR(255) DEFAULT NULL');
        $this->addSql('CREATE INDEX IDX_18B673FE823105 ON unit_type (condominium_id)');
        $this->addSql('ALTER TABLE project_unit ADD floor SMALLINT NOT NULL');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('DROP INDEX IDX_18B673FE823105');
        $this->addSql('ALTER TABLE unit_type DROP code');
        $this->addSql('ALTER TABLE project_unit_type DROP code');
        $this->addSql('CREATE UNIQUE INDEX uniq_18b673fe823105 ON unit_type (condominium_id)');
        $this->addSql('ALTER TABLE project_unit DROP floor');
    }
}
