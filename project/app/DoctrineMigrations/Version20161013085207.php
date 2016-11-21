<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20161013085207 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE client_unit ADD is_run_schedule_auto BOOLEAN DEFAULT \'false\' NOT NULL');
        $this->addSql('ALTER TABLE client_unit ADD is_send_invoice BOOLEAN DEFAULT \'false\' NOT NULL');
        $this->addSql('ALTER TABLE client_unit ADD generated_invoice BOOLEAN DEFAULT \'false\' NOT NULL');
        $this->addSql('ALTER TABLE client_unit ADD day VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE client_unit ADD hour VARCHAR(255) DEFAULT NULL');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE client_unit DROP is_run_schedule_auto');
        $this->addSql('ALTER TABLE client_unit DROP is_send_invoice');
        $this->addSql('ALTER TABLE client_unit DROP generated_invoice');
        $this->addSql('ALTER TABLE client_unit DROP day');
        $this->addSql('ALTER TABLE client_unit DROP hour');
    }
}
