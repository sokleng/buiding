<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20161118164931 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SEQUENCE unit_price_log_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE unit_price_log (id INT NOT NULL, unit_id INT NOT NULL, edit_by_id INT DEFAULT NULL, old_price DOUBLE PRECISION NOT NULL, new_price DOUBLE PRECISION NOT NULL, modify_date TIMESTAMP(0) WITH TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_C427AFC8F8BD700D ON unit_price_log (unit_id)');
        $this->addSql('CREATE INDEX IDX_C427AFC893555579 ON unit_price_log (edit_by_id)');
        $this->addSql('ALTER TABLE unit_price_log ADD CONSTRAINT FK_C427AFC8F8BD700D FOREIGN KEY (unit_id) REFERENCES unit (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE unit_price_log ADD CONSTRAINT FK_C427AFC893555579 FOREIGN KEY (edit_by_id) REFERENCES fos_user (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('DROP SEQUENCE unit_price_log_id_seq CASCADE');
        $this->addSql('DROP TABLE unit_price_log');
    }
}
