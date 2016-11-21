<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160829094040 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');
        $this->addSql('TRUNCATE TABLE fos_user CASCADE');
        $this->addSql('ALTER TABLE fos_user ADD picture_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE fos_user ADD condominium_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE fos_user ADD unit_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE fos_user ADD id_card VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE fos_user ADD start_date TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL');
        $this->addSql('ALTER TABLE fos_user ADD end_date TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL');
        $this->addSql('ALTER TABLE fos_user ADD payment_method VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE fos_user ADD price DOUBLE PRECISION DEFAULT NULL');
        $this->addSql('ALTER TABLE fos_user ADD CONSTRAINT FK_957A6479EE45BDBF FOREIGN KEY (picture_id) REFERENCES database_file (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE fos_user ADD CONSTRAINT FK_957A6479FE823105 FOREIGN KEY (condominium_id) REFERENCES condominium (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE fos_user ADD CONSTRAINT FK_957A6479F8BD700D FOREIGN KEY (unit_id) REFERENCES unit (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE fos_user DROP CONSTRAINT FK_957A6479EE45BDBF');
        $this->addSql('ALTER TABLE fos_user DROP CONSTRAINT FK_957A6479FE823105');
        $this->addSql('ALTER TABLE fos_user DROP CONSTRAINT FK_957A6479F8BD700D');
        $this->addSql('ALTER TABLE fos_user DROP picture_id');
        $this->addSql('ALTER TABLE fos_user DROP condominium_id');
        $this->addSql('ALTER TABLE fos_user DROP unit_id');
        $this->addSql('ALTER TABLE fos_user DROP id_card');
        $this->addSql('ALTER TABLE fos_user DROP start_date');
        $this->addSql('ALTER TABLE fos_user DROP end_date');
        $this->addSql('ALTER TABLE fos_user DROP payment_method');
        $this->addSql('ALTER TABLE fos_user DROP price');
    }
}
