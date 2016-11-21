<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160923133852 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SEQUENCE client_unit_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE client_unit (id INT NOT NULL, picture_id INT DEFAULT NULL, id_card_picture_id INT DEFAULT NULL, unit_id INT DEFAULT NULL, user_id INT DEFAULT NULL, phone_number VARCHAR(255) DEFAULT NULL, id_card VARCHAR(255) DEFAULT NULL, start_date TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, end_date TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, payment_method VARCHAR(255) DEFAULT NULL, price DOUBLE PRECISION DEFAULT NULL, amount INT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_D27CF31EE45BDBF ON client_unit (picture_id)');
        $this->addSql('CREATE INDEX IDX_D27CF316B69E339 ON client_unit (id_card_picture_id)');
        $this->addSql('CREATE INDEX IDX_D27CF31F8BD700D ON client_unit (unit_id)');
        $this->addSql('CREATE INDEX IDX_D27CF31A76ED395 ON client_unit (user_id)');
        $this->addSql('ALTER TABLE client_unit ADD CONSTRAINT FK_D27CF31EE45BDBF FOREIGN KEY (picture_id) REFERENCES database_file (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE client_unit ADD CONSTRAINT FK_D27CF316B69E339 FOREIGN KEY (id_card_picture_id) REFERENCES database_file (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE client_unit ADD CONSTRAINT FK_D27CF31F8BD700D FOREIGN KEY (unit_id) REFERENCES unit (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE client_unit ADD CONSTRAINT FK_D27CF31A76ED395 FOREIGN KEY (user_id) REFERENCES fos_user (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE fos_user DROP CONSTRAINT fk_957a6479ee45bdbf');
        $this->addSql('ALTER TABLE fos_user DROP CONSTRAINT fk_957a6479f8bd700d');
        $this->addSql('ALTER TABLE fos_user DROP CONSTRAINT fk_957a64796b69e339');
        $this->addSql('DROP INDEX idx_957a64796b69e339');
        $this->addSql('DROP INDEX uniq_957a6479f8bd700d');
        $this->addSql('DROP INDEX uniq_957a6479c05fb297');
        $this->addSql('ALTER TABLE fos_user DROP picture_id');
        $this->addSql('ALTER TABLE fos_user DROP unit_id');
        $this->addSql('ALTER TABLE fos_user DROP id_card_picture_id');
        $this->addSql('ALTER TABLE fos_user DROP phone_number');
        $this->addSql('ALTER TABLE fos_user DROP id_card');
        $this->addSql('ALTER TABLE fos_user DROP start_date');
        $this->addSql('ALTER TABLE fos_user DROP end_date');
        $this->addSql('ALTER TABLE fos_user DROP payment_method');
        $this->addSql('ALTER TABLE fos_user DROP price');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('DROP SEQUENCE client_unit_id_seq CASCADE');
        $this->addSql('DROP TABLE client_unit');
        $this->addSql('ALTER TABLE fos_user ADD picture_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE fos_user ADD unit_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE fos_user ADD id_card_picture_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE fos_user ADD phone_number VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE fos_user ADD id_card VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE fos_user ADD start_date TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL');
        $this->addSql('ALTER TABLE fos_user ADD end_date TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL');
        $this->addSql('ALTER TABLE fos_user ADD payment_method VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE fos_user ADD price DOUBLE PRECISION DEFAULT NULL');
        $this->addSql('ALTER TABLE fos_user ADD CONSTRAINT fk_957a6479ee45bdbf FOREIGN KEY (picture_id) REFERENCES database_file (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE fos_user ADD CONSTRAINT fk_957a6479f8bd700d FOREIGN KEY (unit_id) REFERENCES unit (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE fos_user ADD CONSTRAINT fk_957a64796b69e339 FOREIGN KEY (id_card_picture_id) REFERENCES database_file (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_957a64796b69e339 ON fos_user (id_card_picture_id)');
        $this->addSql('CREATE UNIQUE INDEX uniq_957a6479f8bd700d ON fos_user (unit_id)');
        $this->addSql('CREATE UNIQUE INDEX uniq_957a6479c05fb297 ON fos_user (confirmation_token)');
    }
}
