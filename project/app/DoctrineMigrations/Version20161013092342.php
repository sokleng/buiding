<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20161013092342 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SEQUENCE profit_category_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE profit_category (id INT NOT NULL, name VARCHAR(255) NOT NULL, discr VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE expend_category (id INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE income_category (id INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('ALTER TABLE expend_category ADD CONSTRAINT FK_6172635BBF396750 FOREIGN KEY (id) REFERENCES profit_category (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE income_category ADD CONSTRAINT FK_2F2D922FBF396750 FOREIGN KEY (id) REFERENCES profit_category (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');

    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE expend_category DROP CONSTRAINT FK_6172635BBF396750');
        $this->addSql('ALTER TABLE income_category DROP CONSTRAINT FK_2F2D922FBF396750');
        $this->addSql('DROP SEQUENCE profit_category_id_seq CASCADE');
        $this->addSql('DROP TABLE profit_category');
        $this->addSql('DROP TABLE expend_category');
        $this->addSql('DROP TABLE income_category');
    }
}
