<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20161021134726 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE unit ADD currency_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE unit ADD exchange_setting_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE unit ADD CONSTRAINT FK_DCBB0C5338248176 FOREIGN KEY (currency_id) REFERENCES currency (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE unit ADD CONSTRAINT FK_DCBB0C53A21185B5 FOREIGN KEY (exchange_setting_id) REFERENCES exchange_setting (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_DCBB0C5338248176 ON unit (currency_id)');
        $this->addSql('CREATE INDEX IDX_DCBB0C53A21185B5 ON unit (exchange_setting_id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE unit DROP CONSTRAINT FK_DCBB0C5338248176');
        $this->addSql('ALTER TABLE unit DROP CONSTRAINT FK_DCBB0C53A21185B5');
        $this->addSql('DROP INDEX IDX_DCBB0C5338248176');
        $this->addSql('DROP INDEX IDX_DCBB0C53A21185B5');
        $this->addSql('ALTER TABLE unit DROP currency_id');
        $this->addSql('ALTER TABLE unit DROP exchange_setting_id');
    }
}
