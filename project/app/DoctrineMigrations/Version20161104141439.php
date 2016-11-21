<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20161104141439 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE unit DROP CONSTRAINT fk_dcbb0c5338248176');
        $this->addSql('ALTER TABLE unit DROP CONSTRAINT fk_dcbb0c53a21185b5');
        $this->addSql('DROP INDEX idx_dcbb0c5338248176');
        $this->addSql('DROP INDEX idx_dcbb0c53a21185b5');
        $this->addSql('ALTER TABLE unit DROP currency_id');
        $this->addSql('ALTER TABLE unit DROP exchange_setting_id');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE unit ADD currency_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE unit ADD exchange_setting_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE unit ADD CONSTRAINT fk_dcbb0c5338248176 FOREIGN KEY (currency_id) REFERENCES currency (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE unit ADD CONSTRAINT fk_dcbb0c53a21185b5 FOREIGN KEY (exchange_setting_id) REFERENCES exchange_setting (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_dcbb0c5338248176 ON unit (currency_id)');
        $this->addSql('CREATE INDEX idx_dcbb0c53a21185b5 ON unit (exchange_setting_id)');
    }
}
