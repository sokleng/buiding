<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160711090250 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SEQUENCE project_booking_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE project_booking (id INT NOT NULL, unit_id INT NOT NULL, contact_id INT NOT NULL, seller VARCHAR(255) DEFAULT NULL, asking_price DOUBLE PRECISION DEFAULT NULL, discount DOUBLE PRECISION DEFAULT NULL, final_price DOUBLE PRECISION DEFAULT NULL, creation_date TIMESTAMP(0) WITH TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_7612E1BBF8BD700D ON project_booking (unit_id)');
        $this->addSql('CREATE INDEX IDX_7612E1BBE7A1254A ON project_booking (contact_id)');
        $this->addSql('ALTER TABLE project_booking ADD CONSTRAINT FK_7612E1BBF8BD700D FOREIGN KEY (unit_id) REFERENCES project_unit (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE project_booking ADD CONSTRAINT FK_7612E1BBE7A1254A FOREIGN KEY (contact_id) REFERENCES project_contact (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE project_payment DROP CONSTRAINT fk_fb368868166d1f9c');
        $this->addSql('DROP INDEX idx_fb368868166d1f9c');
        $this->addSql('ALTER TABLE project_payment DROP project_id');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('DROP SEQUENCE project_booking_id_seq CASCADE');
        $this->addSql('DROP TABLE project_booking');
        $this->addSql('ALTER TABLE project_payment ADD project_id INT NOT NULL');
        $this->addSql('ALTER TABLE project_payment ADD CONSTRAINT fk_fb368868166d1f9c FOREIGN KEY (project_id) REFERENCES condominium_project (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_fb368868166d1f9c ON project_payment (project_id)');
    }
}
