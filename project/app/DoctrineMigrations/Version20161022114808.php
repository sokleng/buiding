<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20161022114808 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE invoice ADD create_by_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE invoice ADD mark_as_paid_by_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE invoice ADD discount DOUBLE PRECISION DEFAULT NULL');
        $this->addSql('ALTER TABLE invoice ADD payment_date TIMESTAMP(0) WITH TIME ZONE DEFAULT NULL');
        $this->addSql('ALTER TABLE invoice ADD CONSTRAINT FK_906517449E085865 FOREIGN KEY (create_by_id) REFERENCES fos_user (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE invoice ADD CONSTRAINT FK_90651744C54D89A8 FOREIGN KEY (mark_as_paid_by_id) REFERENCES fos_user (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_906517449E085865 ON invoice (create_by_id)');
        $this->addSql('CREATE INDEX IDX_90651744C54D89A8 ON invoice (mark_as_paid_by_id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE invoice DROP CONSTRAINT FK_906517449E085865');
        $this->addSql('ALTER TABLE invoice DROP CONSTRAINT FK_90651744C54D89A8');
        $this->addSql('DROP INDEX IDX_906517449E085865');
        $this->addSql('DROP INDEX IDX_90651744C54D89A8');
        $this->addSql('ALTER TABLE invoice DROP create_by_id');
        $this->addSql('ALTER TABLE invoice DROP mark_as_paid_by_id');
        $this->addSql('ALTER TABLE invoice DROP discount');
        $this->addSql('ALTER TABLE invoice DROP payment_date');
    }
}
