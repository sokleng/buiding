<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20161027103444 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE shopping_cart_item ADD currency_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE shopping_cart_item ADD vat DOUBLE PRECISION DEFAULT NULL');
        $this->addSql('ALTER TABLE shopping_cart_item ADD sub_total DOUBLE PRECISION DEFAULT 0');
        $this->addSql('ALTER TABLE shopping_cart_item ADD grand_total DOUBLE PRECISION DEFAULT 0');
        $this->addSql('ALTER TABLE shopping_cart_item ADD CONSTRAINT FK_E59A1DF438248176 FOREIGN KEY (currency_id) REFERENCES currency (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_E59A1DF438248176 ON shopping_cart_item (currency_id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE shopping_cart_item DROP CONSTRAINT FK_E59A1DF438248176');
        $this->addSql('DROP INDEX IDX_E59A1DF438248176');
        $this->addSql('ALTER TABLE shopping_cart_item DROP currency_id');
        $this->addSql('ALTER TABLE shopping_cart_item DROP vat');
        $this->addSql('ALTER TABLE shopping_cart_item DROP sub_total');
        $this->addSql('ALTER TABLE shopping_cart_item DROP grand_total');
    }
}
