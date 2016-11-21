<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20161013141901 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE profit_category ADD condominium_id INT NOT NULL');
        $this->addSql('ALTER TABLE profit_category ADD CONSTRAINT FK_2EDCAD70FE823105 FOREIGN KEY (condominium_id) REFERENCES condominium (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_2EDCAD70FE823105 ON profit_category (condominium_id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE profit_category DROP CONSTRAINT FK_2EDCAD70FE823105');
        $this->addSql('DROP INDEX IDX_2EDCAD70FE823105');
        $this->addSql('ALTER TABLE profit_category DROP condominium_id');
    }
}
