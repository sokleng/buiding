<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20161028154935 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE issue ADD supplier_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE issue ADD supplier_type SMALLINT DEFAULT NULL');
        $this->addSql('ALTER TABLE issue ADD vat DOUBLE PRECISION DEFAULT NULL');
        $this->addSql('ALTER TABLE issue ADD CONSTRAINT FK_12AD233E2ADD6D8C FOREIGN KEY (supplier_id) REFERENCES supplier (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_12AD233E2ADD6D8C ON issue (supplier_id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE issue DROP CONSTRAINT FK_12AD233E2ADD6D8C');
        $this->addSql('DROP INDEX IDX_12AD233E2ADD6D8C');
        $this->addSql('ALTER TABLE issue DROP supplier_id');
        $this->addSql('ALTER TABLE issue DROP supplier_type');
        $this->addSql('ALTER TABLE issue DROP vat');
    }
}
