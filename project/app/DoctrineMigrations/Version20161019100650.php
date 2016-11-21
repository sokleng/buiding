<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20161019100650 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SEQUENCE exchange_setting_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE exchange_setting (id INT NOT NULL, value JSON NOT NULL, PRIMARY KEY(id))');
        $this->addSql('ALTER TABLE issue ADD exchange_setting_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE issue ADD CONSTRAINT FK_12AD233EA21185B5 FOREIGN KEY (exchange_setting_id) REFERENCES exchange_setting (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_12AD233EA21185B5 ON issue (exchange_setting_id)');
        $this->addSql('ALTER TABLE condominium ADD exchange_setting_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE condominium ADD CONSTRAINT FK_E25F3F22A21185B5 FOREIGN KEY (exchange_setting_id) REFERENCES exchange_setting (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_E25F3F22A21185B5 ON condominium (exchange_setting_id)');
        $this->addSql('ALTER TABLE invoice ADD exchange_setting_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE invoice ADD CONSTRAINT FK_90651744A21185B5 FOREIGN KEY (exchange_setting_id) REFERENCES exchange_setting (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_90651744A21185B5 ON invoice (exchange_setting_id)');
        $this->addSql('ALTER TABLE client_unit ADD exchange_setting_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE client_unit ADD CONSTRAINT FK_D27CF31A21185B5 FOREIGN KEY (exchange_setting_id) REFERENCES exchange_setting (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_D27CF31A21185B5 ON client_unit (exchange_setting_id)');
        $this->addSql('ALTER TABLE service ADD exchange_setting_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE service ADD CONSTRAINT FK_E19D9AD2A21185B5 FOREIGN KEY (exchange_setting_id) REFERENCES exchange_setting (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_E19D9AD2A21185B5 ON service (exchange_setting_id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('DROP SEQUENCE exchange_setting_id_seq CASCADE');
        $this->addSql('DROP TABLE exchange_setting');
        $this->addSql('ALTER TABLE issue DROP CONSTRAINT FK_12AD233EA21185B5');
        $this->addSql('DROP INDEX IDX_12AD233EA21185B5');
        $this->addSql('ALTER TABLE issue DROP exchange_setting_id');
        $this->addSql('ALTER TABLE condominium DROP CONSTRAINT FK_E25F3F22A21185B5');
        $this->addSql('DROP INDEX IDX_E25F3F22A21185B5');
        $this->addSql('ALTER TABLE condominium DROP exchange_setting_id');
        $this->addSql('ALTER TABLE service DROP CONSTRAINT FK_E19D9AD2A21185B5');
        $this->addSql('DROP INDEX IDX_E19D9AD2A21185B5');
        $this->addSql('ALTER TABLE service DROP exchange_setting_id');
        $this->addSql('DROP INDEX UNIQ_957A6479C05FB297');
        $this->addSql('ALTER TABLE client_unit DROP CONSTRAINT FK_D27CF31A21185B5');
        $this->addSql('DROP INDEX IDX_D27CF31A21185B5');
        $this->addSql('ALTER TABLE client_unit DROP exchange_setting_id');
        $this->addSql('ALTER TABLE invoice DROP CONSTRAINT FK_90651744A21185B5');
        $this->addSql('DROP INDEX IDX_90651744A21185B5');
        $this->addSql('ALTER TABLE invoice DROP exchange_setting_id');
    }
}
