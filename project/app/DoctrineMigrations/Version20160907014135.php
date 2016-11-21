<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160907014135 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE UNIQUE INDEX UNIQ_957A6479F8BD700D ON fos_user (unit_id)');
        $this->addSql('ALTER TABLE resident DROP CONSTRAINT FK_1D03DA06F8BD700D');
        $this->addSql('ALTER TABLE resident ADD CONSTRAINT FK_1D03DA06F8BD700D FOREIGN KEY (unit_id) REFERENCES unit (id) ON DELETE SET NULL NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE resident ADD CONSTRAINT fk_1d03da06f8bd700d FOREIGN KEY (unit_id) REFERENCES unit (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('DROP INDEX UNIQ_957A6479F8BD700D');
        $this->addSql('CREATE INDEX IDX_957A6479F8BD700D ON fos_user (unit_id)');
    }
}
