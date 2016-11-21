<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160711040326 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('DELETE FROM project_payment');
        $this->addSql('ALTER TABLE project_payment ADD unit_id INT NOT NULL');
        $this->addSql('ALTER TABLE project_payment ADD CONSTRAINT FK_FB368868F8BD700D FOREIGN KEY (unit_id) REFERENCES project_unit (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_FB368868F8BD700D ON project_payment (unit_id)');
        $this->addSql('DELETE FROM project_unit');
        $this->addSql('ALTER TABLE project_unit ADD room_number VARCHAR(255) NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_E52AC44BD7DED995 ON project_unit (room_number)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE project_payment DROP CONSTRAINT FK_FB368868F8BD700D');
        $this->addSql('DROP INDEX IDX_FB368868F8BD700D');
        $this->addSql('ALTER TABLE project_payment DROP unit_id');
        $this->addSql('ALTER TABLE project_unit DROP room_number');
    }
}
