<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20161019164819 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE client_unit ADD currency_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE client_unit ADD CONSTRAINT FK_D27CF3138248176 FOREIGN KEY (currency_id) REFERENCES currency (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_D27CF3138248176 ON client_unit (currency_id)');
        $this->addSql('ALTER TABLE invoice ADD currency_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE invoice ADD CONSTRAINT FK_9065174438248176 FOREIGN KEY (currency_id) REFERENCES currency (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_9065174438248176 ON invoice (currency_id)');
        $this->addSql('ALTER TABLE issue ADD currency_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE issue ADD CONSTRAINT FK_12AD233E38248176 FOREIGN KEY (currency_id) REFERENCES currency (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_12AD233E38248176 ON issue (currency_id)');
        $this->addSql('ALTER TABLE issue ALTER price TYPE DOUBLE PRECISION');
        $this->addSql('ALTER TABLE issue ALTER price DROP DEFAULT');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE client_unit DROP CONSTRAINT FK_D27CF3138248176');
        $this->addSql('DROP INDEX IDX_D27CF3138248176');
        $this->addSql('ALTER TABLE client_unit DROP currency_id');
        $this->addSql('ALTER TABLE invoice DROP CONSTRAINT FK_9065174438248176');
        $this->addSql('DROP INDEX IDX_9065174438248176');
        $this->addSql('ALTER TABLE invoice DROP currency_id');
        $this->addSql('ALTER TABLE issue DROP CONSTRAINT FK_12AD233E38248176');
        $this->addSql('DROP INDEX IDX_12AD233E38248176');
        $this->addSql('ALTER TABLE issue DROP currency_id');
        $this->addSql('ALTER TABLE issue ALTER price TYPE SMALLINT');
        $this->addSql('ALTER TABLE issue ALTER price DROP DEFAULT');
    }
}
