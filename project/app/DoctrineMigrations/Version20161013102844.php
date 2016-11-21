<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20161013102844 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SEQUENCE invoice_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE invoice (id INT NOT NULL, vat DOUBLE PRECISION DEFAULT NULL, sub_total DOUBLE PRECISION NOT NULL, grand_total DOUBLE PRECISION NOT NULL, discr VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE income (id INT NOT NULL, income_category_id INT DEFAULT NULL, client_id INT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_3FA862D053F8702F ON income (income_category_id)');
        $this->addSql('CREATE INDEX IDX_3FA862D019EB6921 ON income (client_id)');
        $this->addSql('CREATE TABLE expend (id INT NOT NULL, expend_category_id INT DEFAULT NULL, issue_id INT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_B4DBDE7BF6732C74 ON expend (expend_category_id)');
        $this->addSql('CREATE INDEX IDX_B4DBDE7B5E7AA58C ON expend (issue_id)');
        $this->addSql('ALTER TABLE income ADD CONSTRAINT FK_3FA862D053F8702F FOREIGN KEY (income_category_id) REFERENCES income_category (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE income ADD CONSTRAINT FK_3FA862D019EB6921 FOREIGN KEY (client_id) REFERENCES client_unit (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE income ADD CONSTRAINT FK_3FA862D0BF396750 FOREIGN KEY (id) REFERENCES invoice (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE expend ADD CONSTRAINT FK_B4DBDE7BF6732C74 FOREIGN KEY (expend_category_id) REFERENCES expend_category (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE expend ADD CONSTRAINT FK_B4DBDE7B5E7AA58C FOREIGN KEY (issue_id) REFERENCES issue (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE expend ADD CONSTRAINT FK_B4DBDE7BBF396750 FOREIGN KEY (id) REFERENCES invoice (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE income DROP CONSTRAINT FK_3FA862D0BF396750');
        $this->addSql('ALTER TABLE expend DROP CONSTRAINT FK_B4DBDE7BBF396750');
        $this->addSql('DROP SEQUENCE invoice_id_seq CASCADE');
        $this->addSql('DROP TABLE invoice');
        $this->addSql('DROP TABLE income');
        $this->addSql('DROP TABLE expend');
    }
}
