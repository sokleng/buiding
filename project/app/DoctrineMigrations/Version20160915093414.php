<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160915093414 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SEQUENCE currency_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE currency (id INT NOT NULL, currency VARCHAR(255) NOT NULL, sign VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('ALTER TABLE fos_user ALTER username TYPE VARCHAR(180)');
        $this->addSql('ALTER TABLE fos_user ALTER username_canonical TYPE VARCHAR(180)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_957A6479C05FB297 ON fos_user (confirmation_token)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE currency_id_seq CASCADE');
        $this->addSql('DROP TABLE currency');
        $this->addSql('DROP INDEX UNIQ_957A6479C05FB297');
        $this->addSql('ALTER TABLE fos_user ALTER username TYPE VARCHAR(255)');
        $this->addSql('ALTER TABLE fos_user ALTER username_canonical TYPE VARCHAR(255)');
    }
}
