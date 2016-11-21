<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20161010145124 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE fos_user ADD picture_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE fos_user ADD phone_number VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE fos_user ADD CONSTRAINT FK_957A6479EE45BDBF FOREIGN KEY (picture_id) REFERENCES database_file (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_957A6479EE45BDBF ON fos_user (picture_id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE fos_user DROP CONSTRAINT FK_957A6479EE45BDBF');
        $this->addSql('DROP INDEX IDX_957A6479EE45BDBF');
        $this->addSql('ALTER TABLE fos_user DROP picture_id');
        $this->addSql('ALTER TABLE fos_user DROP phone_number');
    }
}
