<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20161024095246 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SEQUENCE supplier_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE supplier (id INT NOT NULL, condominium_id INT NOT NULL, name VARCHAR(255) NOT NULL, phone_number VARCHAR(255) DEFAULT NULL, address TEXT NOT NULL, discr VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_9B2A6C7EFE823105 ON supplier (condominium_id)');
        $this->addSql('CREATE TABLE company_supplier (id INT NOT NULL, vatin INT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE individual_supplier (id INT NOT NULL, id_card VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('ALTER TABLE supplier ADD CONSTRAINT FK_9B2A6C7EFE823105 FOREIGN KEY (condominium_id) REFERENCES condominium (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE company_supplier ADD CONSTRAINT FK_83BD7913BF396750 FOREIGN KEY (id) REFERENCES supplier (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE individual_supplier ADD CONSTRAINT FK_81A4F62BF396750 FOREIGN KEY (id) REFERENCES supplier (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE company_supplier DROP CONSTRAINT FK_83BD7913BF396750');
        $this->addSql('ALTER TABLE individual_supplier DROP CONSTRAINT FK_81A4F62BF396750');
        $this->addSql('DROP SEQUENCE supplier_id_seq CASCADE');
        $this->addSql('DROP TABLE supplier');
        $this->addSql('DROP TABLE company_supplier');
        $this->addSql('DROP TABLE individual_supplier');
    }
}
