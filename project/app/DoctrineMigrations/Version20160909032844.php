<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160909032844 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('TRUNCATE TABLE news CASCADE');
        $this->addSql('ALTER TABLE news ADD picture_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE news ADD publish_date TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL');
        $this->addSql('ALTER TABLE news ADD end_date TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL');
        $this->addSql('ALTER TABLE news ADD is_published BOOLEAN DEFAULT \'false\' NOT NULL');
        $this->addSql('ALTER TABLE news ADD description TEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE news RENAME COLUMN content TO short_description');
        $this->addSql('ALTER TABLE news ADD CONSTRAINT FK_1DD39950EE45BDBF FOREIGN KEY (picture_id) REFERENCES database_file (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_1DD39950EE45BDBF ON news (picture_id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE news DROP CONSTRAINT FK_1DD39950EE45BDBF');
        $this->addSql('DROP INDEX IDX_1DD39950EE45BDBF');
        $this->addSql('ALTER TABLE news ADD content TEXT NOT NULL');
        $this->addSql('ALTER TABLE news DROP picture_id');
        $this->addSql('ALTER TABLE news DROP short_description');
        $this->addSql('ALTER TABLE news DROP publish_date');
        $this->addSql('ALTER TABLE news DROP end_date');
        $this->addSql('ALTER TABLE news DROP is_published');
        $this->addSql('ALTER TABLE news DROP description');

    }
}
