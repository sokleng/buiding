<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160729091705 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');
        $this->addSql('DELETE FROM developer_payment');
        $this->addSql('ALTER TABLE developer_payment ADD realty_company_id INT NOT NULL');
        $this->addSql('ALTER TABLE developer_payment ADD CONSTRAINT FK_3B662A63B6B89A2 FOREIGN KEY (realty_company_id) REFERENCES realty_company (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_3B662A63B6B89A2 ON developer_payment (realty_company_id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE developer_payment DROP CONSTRAINT FK_3B662A63B6B89A2');
        $this->addSql('DROP INDEX IDX_3B662A63B6B89A2');
        $this->addSql('ALTER TABLE developer_payment DROP realty_company_id');
    }
}
