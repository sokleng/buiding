<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160725083917 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('DROP SEQUENCE project_payment_id_seq CASCADE');
        $this->addSql('CREATE SEQUENCE payments_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE payments (id INT NOT NULL, contact_id INT NOT NULL, receiver VARCHAR(255) NOT NULL, payment_method VARCHAR(255) NOT NULL, amount DOUBLE PRECISION NOT NULL, received BOOLEAN NOT NULL, payment_date TIMESTAMP(0) WITH TIME ZONE DEFAULT NULL, creation_date TIMESTAMP(0) WITH TIME ZONE NOT NULL, description TEXT NOT NULL, discr VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_65D29B32E7A1254A ON payments (contact_id)');
        $this->addSql('ALTER TABLE payments ADD CONSTRAINT FK_65D29B32E7A1254A FOREIGN KEY (contact_id) REFERENCES company_contact (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE project_payment DROP CONSTRAINT fk_fb368868e7a1254a');
        $this->addSql('DROP INDEX idx_fb368868e7a1254a');
        $this->addSql('ALTER TABLE project_payment DROP contact_id');
        $this->addSql('ALTER TABLE project_payment DROP receiver');
        $this->addSql('ALTER TABLE project_payment DROP payment_method');
        $this->addSql('ALTER TABLE project_payment DROP creation_date');
        $this->addSql('ALTER TABLE project_payment DROP description');
        $this->addSql('ALTER TABLE project_payment DROP amount');
        $this->addSql('ALTER TABLE project_payment DROP received');
        $this->addSql('ALTER TABLE project_payment ADD CONSTRAINT FK_FB368868BF396750 FOREIGN KEY (id) REFERENCES payments (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE project_payment DROP CONSTRAINT FK_FB368868BF396750');
        $this->addSql('DROP SEQUENCE payments_id_seq CASCADE');
        $this->addSql('CREATE SEQUENCE project_payment_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('DROP TABLE payments');
        $this->addSql('ALTER TABLE project_payment ADD contact_id INT NOT NULL');
        $this->addSql('ALTER TABLE project_payment ADD receiver VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE project_payment ADD payment_method VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE project_payment ADD creation_date TIMESTAMP(0) WITH TIME ZONE NOT NULL');
        $this->addSql('ALTER TABLE project_payment ADD description TEXT NOT NULL');
        $this->addSql('ALTER TABLE project_payment ADD amount DOUBLE PRECISION NOT NULL');
        $this->addSql('ALTER TABLE project_payment ADD received BOOLEAN NOT NULL');
        $this->addSql('ALTER TABLE project_payment ADD CONSTRAINT fk_fb368868e7a1254a FOREIGN KEY (contact_id) REFERENCES company_contact (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_fb368868e7a1254a ON project_payment (contact_id)');
    }
}
