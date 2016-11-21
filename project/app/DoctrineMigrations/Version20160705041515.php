<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160705041515 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SEQUENCE fos_user_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE city_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE condominium_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE news_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE country_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE database_file_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE district_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE feedback_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE issue_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE issue_comment_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE landlord_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE resident_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE service_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE service_availability_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE service_provider_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE service_unavailability_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE unit_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE unit_type_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE generic_order_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE shop_item_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE shopping_cart_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE shopping_cart_item_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE condominium_project_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE project_contact_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE project_unit_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE project_unit_status_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE project_unit_type_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE fos_user (id INT NOT NULL, username VARCHAR(255) NOT NULL, username_canonical VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, last_login TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, expires_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, confirmation_token VARCHAR(255) DEFAULT NULL, password_requested_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, credentials_expire_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, phone_number VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, email VARCHAR(255) DEFAULT NULL, enabled BOOLEAN DEFAULT NULL, salt VARCHAR(255) DEFAULT NULL, locked BOOLEAN DEFAULT NULL, expired BOOLEAN DEFAULT NULL, email_canonical VARCHAR(255) DEFAULT NULL, credentials_expired BOOLEAN DEFAULT NULL, roles TEXT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_957A647992FC23A8 ON fos_user (username_canonical)');
        $this->addSql('COMMENT ON COLUMN fos_user.roles IS \'(DC2Type:array)\'');
        $this->addSql('CREATE TABLE city (id INT NOT NULL, country_id INT NOT NULL, creation_date TIMESTAMP(0) WITH TIME ZONE NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_2D5B0234F92F3E70 ON city (country_id)');
        $this->addSql('CREATE TABLE condominium (id INT NOT NULL, district_id INT NOT NULL, creation_date TIMESTAMP(0) WITH TIME ZONE NOT NULL, name VARCHAR(255) NOT NULL, address TEXT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_E25F3F22B08FA272 ON condominium (district_id)');
        $this->addSql('CREATE TABLE condominium_user (condominium_id INT NOT NULL, user_id INT NOT NULL, PRIMARY KEY(condominium_id, user_id))');
        $this->addSql('CREATE INDEX IDX_C857CA22FE823105 ON condominium_user (condominium_id)');
        $this->addSql('CREATE INDEX IDX_C857CA22A76ED395 ON condominium_user (user_id)');
        $this->addSql('CREATE TABLE news (id INT NOT NULL, author_id INT NOT NULL, title VARCHAR(255) NOT NULL, content TEXT NOT NULL, creation_date TIMESTAMP(0) WITH TIME ZONE NOT NULL, discr VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_1DD39950F675F31B ON news (author_id)');
        $this->addSql('CREATE TABLE news_condominium (id INT NOT NULL, condominium_id INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_CB6A896DFE823105 ON news_condominium (condominium_id)');
        $this->addSql('CREATE TABLE country (id INT NOT NULL, code VARCHAR(255) NOT NULL, creation_date TIMESTAMP(0) WITH TIME ZONE NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE database_file (id INT NOT NULL, data BYTEA NOT NULL, mime_type VARCHAR(255) NOT NULL, extension VARCHAR(255) NOT NULL, creation_date TIMESTAMP(0) WITH TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE district (id INT NOT NULL, city_id INT NOT NULL, creation_date TIMESTAMP(0) WITH TIME ZONE NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_31C154878BAC62AF ON district (city_id)');
        $this->addSql('CREATE TABLE feedback (id INT NOT NULL, condominium_id INT DEFAULT NULL, user_id INT NOT NULL, message TEXT NOT NULL, read BOOLEAN NOT NULL, creation_date TIMESTAMP(0) WITH TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_D2294458FE823105 ON feedback (condominium_id)');
        $this->addSql('CREATE INDEX IDX_D2294458A76ED395 ON feedback (user_id)');
        $this->addSql('CREATE TABLE issue (id INT NOT NULL, unit_id INT NOT NULL, user_id INT NOT NULL, status SMALLINT NOT NULL, closing_date TIMESTAMP(0) WITH TIME ZONE DEFAULT NULL, creation_date TIMESTAMP(0) WITH TIME ZONE NOT NULL, description TEXT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_12AD233EF8BD700D ON issue (unit_id)');
        $this->addSql('CREATE INDEX IDX_12AD233EA76ED395 ON issue (user_id)');
        $this->addSql('CREATE TABLE issue_comment (id INT NOT NULL, issue_id INT NOT NULL, user_id INT NOT NULL, comment TEXT NOT NULL, read_by_resident BOOLEAN NOT NULL, read_by_management BOOLEAN NOT NULL, creation_date TIMESTAMP(0) WITH TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_318C178D5E7AA58C ON issue_comment (issue_id)');
        $this->addSql('CREATE INDEX IDX_318C178DA76ED395 ON issue_comment (user_id)');
        $this->addSql('CREATE TABLE landlord (id INT NOT NULL, user_id INT NOT NULL, creation_date TIMESTAMP(0) WITH TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_F446E8F8A76ED395 ON landlord (user_id)');
        $this->addSql('CREATE TABLE news_platform (id INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE resident (id INT NOT NULL, unit_id INT NOT NULL, user_id INT NOT NULL, residency_start TIMESTAMP(0) WITH TIME ZONE DEFAULT NULL, residency_end TIMESTAMP(0) WITH TIME ZONE DEFAULT NULL, creation_date TIMESTAMP(0) WITH TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_1D03DA06F8BD700D ON resident (unit_id)');
        $this->addSql('CREATE INDEX IDX_1D03DA06A76ED395 ON resident (user_id)');
        $this->addSql('CREATE TABLE service (id INT NOT NULL, service_provider_id INT NOT NULL, type SMALLINT NOT NULL, title VARCHAR(255) NOT NULL, description VARCHAR(255) DEFAULT NULL, creation_date TIMESTAMP(0) WITH TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_E19D9AD2C6C98E06 ON service (service_provider_id)');
        $this->addSql('CREATE TABLE service_condominium (service_id INT NOT NULL, condominium_id INT NOT NULL, PRIMARY KEY(service_id, condominium_id))');
        $this->addSql('CREATE INDEX IDX_2EC5198EED5CA9E6 ON service_condominium (service_id)');
        $this->addSql('CREATE INDEX IDX_2EC5198EFE823105 ON service_condominium (condominium_id)');
        $this->addSql('CREATE TABLE service_user (service_id INT NOT NULL, user_id INT NOT NULL, PRIMARY KEY(service_id, user_id))');
        $this->addSql('CREATE INDEX IDX_43D062A5ED5CA9E6 ON service_user (service_id)');
        $this->addSql('CREATE INDEX IDX_43D062A5A76ED395 ON service_user (user_id)');
        $this->addSql('CREATE TABLE service_availability (id INT NOT NULL, service_id INT NOT NULL, opening_time SMALLINT NOT NULL, closing_time SMALLINT NOT NULL, day_of_the_week_start SMALLINT NOT NULL, day_of_the_week_end SMALLINT NOT NULL, creation_date TIMESTAMP(0) WITH TIME ZONE NOT NULL, enabled BOOLEAN DEFAULT \'false\' NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_E01BD75AED5CA9E6 ON service_availability (service_id)');
        $this->addSql('CREATE TABLE news_service (id INT NOT NULL, service_id INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_B6798634ED5CA9E6 ON news_service (service_id)');
        $this->addSql('CREATE TABLE service_provider (id INT NOT NULL, company_name VARCHAR(255) NOT NULL, contact_number VARCHAR(255) NOT NULL, creation_date TIMESTAMP(0) WITH TIME ZONE NOT NULL, description TEXT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE service_provider_user (service_provider_id INT NOT NULL, user_id INT NOT NULL, PRIMARY KEY(service_provider_id, user_id))');
        $this->addSql('CREATE INDEX IDX_B8A40FB7C6C98E06 ON service_provider_user (service_provider_id)');
        $this->addSql('CREATE INDEX IDX_B8A40FB7A76ED395 ON service_provider_user (user_id)');
        $this->addSql('CREATE TABLE service_unavailability (id INT NOT NULL, service_id INT NOT NULL, start_date_time TIMESTAMP(0) WITH TIME ZONE NOT NULL, end_date_time TIMESTAMP(0) WITH TIME ZONE NOT NULL, creation_date TIMESTAMP(0) WITH TIME ZONE NOT NULL, enabled BOOLEAN DEFAULT \'false\' NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_C664B67ED5CA9E6 ON service_unavailability (service_id)');
        $this->addSql('CREATE TABLE unit (id INT NOT NULL, condominium_id INT NOT NULL, type_id INT DEFAULT NULL, landlord_id INT DEFAULT NULL, floor SMALLINT NOT NULL, room_number VARCHAR(10) NOT NULL, creation_date TIMESTAMP(0) WITH TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_DCBB0C53FE823105 ON unit (condominium_id)');
        $this->addSql('CREATE INDEX IDX_DCBB0C53C54C8C93 ON unit (type_id)');
        $this->addSql('CREATE INDEX IDX_DCBB0C53D48E7AED ON unit (landlord_id)');
        $this->addSql('CREATE TABLE unit_type (id INT NOT NULL, condominium_id INT NOT NULL, creation_date TIMESTAMP(0) WITH TIME ZONE NOT NULL, size DOUBLE PRECISION NOT NULL, room_count SMALLINT NOT NULL, bedroom_count SMALLINT NOT NULL, bathroom_count SMALLINT NOT NULL, description VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_18B673FE823105 ON unit_type (condominium_id)');
        $this->addSql('CREATE TABLE generic_order (id INT NOT NULL, shopping_cart_id INT NOT NULL, service_id INT DEFAULT NULL, unit_id INT NOT NULL, client_id INT NOT NULL, status SMALLINT NOT NULL, expected_delivery_time TIMESTAMP(0) WITH TIME ZONE NOT NULL, comments TEXT DEFAULT NULL, creation_date TIMESTAMP(0) WITH TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_9C152B8A45F80CD ON generic_order (shopping_cart_id)');
        $this->addSql('CREATE INDEX IDX_9C152B8AED5CA9E6 ON generic_order (service_id)');
        $this->addSql('CREATE INDEX IDX_9C152B8AF8BD700D ON generic_order (unit_id)');
        $this->addSql('CREATE INDEX IDX_9C152B8A19EB6921 ON generic_order (client_id)');
        $this->addSql('CREATE TABLE shop_item (id INT NOT NULL, service_id INT NOT NULL, picture_id INT DEFAULT NULL, price DOUBLE PRECISION NOT NULL, reference VARCHAR(255) DEFAULT NULL, creation_date TIMESTAMP(0) WITH TIME ZONE NOT NULL, enabled BOOLEAN DEFAULT \'false\' NOT NULL, name VARCHAR(255) NOT NULL, description TEXT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_DEE9C365ED5CA9E6 ON shop_item (service_id)');
        $this->addSql('CREATE INDEX IDX_DEE9C365EE45BDBF ON shop_item (picture_id)');
        $this->addSql('CREATE TABLE shopping_cart (id INT NOT NULL, user_id INT NOT NULL, service_id INT NOT NULL, is_locked BOOLEAN NOT NULL, creation_date TIMESTAMP(0) WITH TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_72AAD4F6A76ED395 ON shopping_cart (user_id)');
        $this->addSql('CREATE INDEX IDX_72AAD4F6ED5CA9E6 ON shopping_cart (service_id)');
        $this->addSql('CREATE TABLE shopping_cart_shopping_cart_item (shopping_cart_id INT NOT NULL, shopping_cart_item_id INT NOT NULL, PRIMARY KEY(shopping_cart_id, shopping_cart_item_id))');
        $this->addSql('CREATE INDEX IDX_F577858145F80CD ON shopping_cart_shopping_cart_item (shopping_cart_id)');
        $this->addSql('CREATE INDEX IDX_F57785813B3A089F ON shopping_cart_shopping_cart_item (shopping_cart_item_id)');
        $this->addSql('CREATE TABLE shopping_cart_item (id INT NOT NULL, shop_item_id INT NOT NULL, quantity SMALLINT NOT NULL, creation_date TIMESTAMP(0) WITH TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_E59A1DF4115C1274 ON shopping_cart_item (shop_item_id)');
        $this->addSql('CREATE TABLE condominium_project (id INT NOT NULL, contact_name VARCHAR(255) NOT NULL, contact_number VARCHAR(255) NOT NULL, creation_date TIMESTAMP(0) WITH TIME ZONE NOT NULL, name VARCHAR(255) NOT NULL, address TEXT NOT NULL, description TEXT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE condominium_project_project_contact (condominium_project_id INT NOT NULL, project_contact_id INT NOT NULL, PRIMARY KEY(condominium_project_id, project_contact_id))');
        $this->addSql('CREATE INDEX IDX_D18919905E30ACFC ON condominium_project_project_contact (condominium_project_id)');
        $this->addSql('CREATE INDEX IDX_D1891990FF075E78 ON condominium_project_project_contact (project_contact_id)');
        $this->addSql('CREATE TABLE condominium_project_user (condominium_project_id INT NOT NULL, user_id INT NOT NULL, PRIMARY KEY(condominium_project_id, user_id))');
        $this->addSql('CREATE INDEX IDX_8397DF9D5E30ACFC ON condominium_project_user (condominium_project_id)');
        $this->addSql('CREATE INDEX IDX_8397DF9DA76ED395 ON condominium_project_user (user_id)');
        $this->addSql('CREATE TABLE project_contact (id INT NOT NULL, comment TEXT DEFAULT NULL, address VARCHAR(255) DEFAULT NULL, phone_number VARCHAR(255) DEFAULT NULL, creation_date TIMESTAMP(0) WITH TIME ZONE NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE project_unit (id INT NOT NULL, type_id INT DEFAULT NULL, status_id INT DEFAULT NULL, project_id INT DEFAULT NULL, creation_date TIMESTAMP(0) WITH TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_E52AC44BC54C8C93 ON project_unit (type_id)');
        $this->addSql('CREATE INDEX IDX_E52AC44B6BF700BD ON project_unit (status_id)');
        $this->addSql('CREATE INDEX IDX_E52AC44B166D1F9C ON project_unit (project_id)');
        $this->addSql('CREATE TABLE project_unit_project_contact (project_unit_id INT NOT NULL, project_contact_id INT NOT NULL, PRIMARY KEY(project_unit_id, project_contact_id))');
        $this->addSql('CREATE INDEX IDX_71E7D04A6EA37C68 ON project_unit_project_contact (project_unit_id)');
        $this->addSql('CREATE INDEX IDX_71E7D04AFF075E78 ON project_unit_project_contact (project_contact_id)');
        $this->addSql('CREATE TABLE project_unit_status (id INT NOT NULL, project_id INT DEFAULT NULL, closed_status BOOLEAN NOT NULL, creation_date TIMESTAMP(0) WITH TIME ZONE NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_24967911166D1F9C ON project_unit_status (project_id)');
        $this->addSql('CREATE TABLE project_unit_type (id INT NOT NULL, project_id INT DEFAULT NULL, creation_date TIMESTAMP(0) WITH TIME ZONE NOT NULL, size DOUBLE PRECISION NOT NULL, room_count SMALLINT NOT NULL, bedroom_count SMALLINT NOT NULL, bathroom_count SMALLINT NOT NULL, description VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_1124F3A4166D1F9C ON project_unit_type (project_id)');
        $this->addSql('ALTER TABLE city ADD CONSTRAINT FK_2D5B0234F92F3E70 FOREIGN KEY (country_id) REFERENCES country (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE condominium ADD CONSTRAINT FK_E25F3F22B08FA272 FOREIGN KEY (district_id) REFERENCES district (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE condominium_user ADD CONSTRAINT FK_C857CA22FE823105 FOREIGN KEY (condominium_id) REFERENCES condominium (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE condominium_user ADD CONSTRAINT FK_C857CA22A76ED395 FOREIGN KEY (user_id) REFERENCES fos_user (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE news ADD CONSTRAINT FK_1DD39950F675F31B FOREIGN KEY (author_id) REFERENCES fos_user (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE news_condominium ADD CONSTRAINT FK_CB6A896DFE823105 FOREIGN KEY (condominium_id) REFERENCES condominium (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE news_condominium ADD CONSTRAINT FK_CB6A896DBF396750 FOREIGN KEY (id) REFERENCES news (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE district ADD CONSTRAINT FK_31C154878BAC62AF FOREIGN KEY (city_id) REFERENCES city (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE feedback ADD CONSTRAINT FK_D2294458FE823105 FOREIGN KEY (condominium_id) REFERENCES condominium (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE feedback ADD CONSTRAINT FK_D2294458A76ED395 FOREIGN KEY (user_id) REFERENCES fos_user (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE issue ADD CONSTRAINT FK_12AD233EF8BD700D FOREIGN KEY (unit_id) REFERENCES unit (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE issue ADD CONSTRAINT FK_12AD233EA76ED395 FOREIGN KEY (user_id) REFERENCES fos_user (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE issue_comment ADD CONSTRAINT FK_318C178D5E7AA58C FOREIGN KEY (issue_id) REFERENCES issue (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE issue_comment ADD CONSTRAINT FK_318C178DA76ED395 FOREIGN KEY (user_id) REFERENCES fos_user (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE landlord ADD CONSTRAINT FK_F446E8F8A76ED395 FOREIGN KEY (user_id) REFERENCES fos_user (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE news_platform ADD CONSTRAINT FK_706C739ABF396750 FOREIGN KEY (id) REFERENCES news (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE resident ADD CONSTRAINT FK_1D03DA06F8BD700D FOREIGN KEY (unit_id) REFERENCES unit (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE resident ADD CONSTRAINT FK_1D03DA06A76ED395 FOREIGN KEY (user_id) REFERENCES fos_user (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE service ADD CONSTRAINT FK_E19D9AD2C6C98E06 FOREIGN KEY (service_provider_id) REFERENCES service_provider (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE service_condominium ADD CONSTRAINT FK_2EC5198EED5CA9E6 FOREIGN KEY (service_id) REFERENCES service (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE service_condominium ADD CONSTRAINT FK_2EC5198EFE823105 FOREIGN KEY (condominium_id) REFERENCES condominium (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE service_user ADD CONSTRAINT FK_43D062A5ED5CA9E6 FOREIGN KEY (service_id) REFERENCES service (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE service_user ADD CONSTRAINT FK_43D062A5A76ED395 FOREIGN KEY (user_id) REFERENCES fos_user (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE service_availability ADD CONSTRAINT FK_E01BD75AED5CA9E6 FOREIGN KEY (service_id) REFERENCES service (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE news_service ADD CONSTRAINT FK_B6798634ED5CA9E6 FOREIGN KEY (service_id) REFERENCES service (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE news_service ADD CONSTRAINT FK_B6798634BF396750 FOREIGN KEY (id) REFERENCES news (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE service_provider_user ADD CONSTRAINT FK_B8A40FB7C6C98E06 FOREIGN KEY (service_provider_id) REFERENCES service_provider (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE service_provider_user ADD CONSTRAINT FK_B8A40FB7A76ED395 FOREIGN KEY (user_id) REFERENCES fos_user (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE service_unavailability ADD CONSTRAINT FK_C664B67ED5CA9E6 FOREIGN KEY (service_id) REFERENCES service (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE unit ADD CONSTRAINT FK_DCBB0C53FE823105 FOREIGN KEY (condominium_id) REFERENCES condominium (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE unit ADD CONSTRAINT FK_DCBB0C53C54C8C93 FOREIGN KEY (type_id) REFERENCES unit_type (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE unit ADD CONSTRAINT FK_DCBB0C53D48E7AED FOREIGN KEY (landlord_id) REFERENCES landlord (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE unit_type ADD CONSTRAINT FK_18B673FE823105 FOREIGN KEY (condominium_id) REFERENCES condominium (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE generic_order ADD CONSTRAINT FK_9C152B8A45F80CD FOREIGN KEY (shopping_cart_id) REFERENCES shopping_cart (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE generic_order ADD CONSTRAINT FK_9C152B8AED5CA9E6 FOREIGN KEY (service_id) REFERENCES service (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE generic_order ADD CONSTRAINT FK_9C152B8AF8BD700D FOREIGN KEY (unit_id) REFERENCES unit (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE generic_order ADD CONSTRAINT FK_9C152B8A19EB6921 FOREIGN KEY (client_id) REFERENCES fos_user (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE shop_item ADD CONSTRAINT FK_DEE9C365ED5CA9E6 FOREIGN KEY (service_id) REFERENCES service (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE shop_item ADD CONSTRAINT FK_DEE9C365EE45BDBF FOREIGN KEY (picture_id) REFERENCES database_file (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE shopping_cart ADD CONSTRAINT FK_72AAD4F6A76ED395 FOREIGN KEY (user_id) REFERENCES fos_user (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE shopping_cart ADD CONSTRAINT FK_72AAD4F6ED5CA9E6 FOREIGN KEY (service_id) REFERENCES service (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE shopping_cart_shopping_cart_item ADD CONSTRAINT FK_F577858145F80CD FOREIGN KEY (shopping_cart_id) REFERENCES shopping_cart (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE shopping_cart_shopping_cart_item ADD CONSTRAINT FK_F57785813B3A089F FOREIGN KEY (shopping_cart_item_id) REFERENCES shopping_cart_item (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE shopping_cart_item ADD CONSTRAINT FK_E59A1DF4115C1274 FOREIGN KEY (shop_item_id) REFERENCES shop_item (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE condominium_project_project_contact ADD CONSTRAINT FK_D18919905E30ACFC FOREIGN KEY (condominium_project_id) REFERENCES condominium_project (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE condominium_project_project_contact ADD CONSTRAINT FK_D1891990FF075E78 FOREIGN KEY (project_contact_id) REFERENCES project_contact (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE condominium_project_user ADD CONSTRAINT FK_8397DF9D5E30ACFC FOREIGN KEY (condominium_project_id) REFERENCES condominium_project (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE condominium_project_user ADD CONSTRAINT FK_8397DF9DA76ED395 FOREIGN KEY (user_id) REFERENCES fos_user (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE project_unit ADD CONSTRAINT FK_E52AC44BC54C8C93 FOREIGN KEY (type_id) REFERENCES project_unit_type (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE project_unit ADD CONSTRAINT FK_E52AC44B6BF700BD FOREIGN KEY (status_id) REFERENCES project_unit_status (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE project_unit ADD CONSTRAINT FK_E52AC44B166D1F9C FOREIGN KEY (project_id) REFERENCES condominium_project (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE project_unit_project_contact ADD CONSTRAINT FK_71E7D04A6EA37C68 FOREIGN KEY (project_unit_id) REFERENCES project_unit (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE project_unit_project_contact ADD CONSTRAINT FK_71E7D04AFF075E78 FOREIGN KEY (project_contact_id) REFERENCES project_contact (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE project_unit_status ADD CONSTRAINT FK_24967911166D1F9C FOREIGN KEY (project_id) REFERENCES condominium_project (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE project_unit_type ADD CONSTRAINT FK_1124F3A4166D1F9C FOREIGN KEY (project_id) REFERENCES condominium_project (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE condominium_user DROP CONSTRAINT FK_C857CA22A76ED395');
        $this->addSql('ALTER TABLE news DROP CONSTRAINT FK_1DD39950F675F31B');
        $this->addSql('ALTER TABLE feedback DROP CONSTRAINT FK_D2294458A76ED395');
        $this->addSql('ALTER TABLE issue DROP CONSTRAINT FK_12AD233EA76ED395');
        $this->addSql('ALTER TABLE issue_comment DROP CONSTRAINT FK_318C178DA76ED395');
        $this->addSql('ALTER TABLE landlord DROP CONSTRAINT FK_F446E8F8A76ED395');
        $this->addSql('ALTER TABLE resident DROP CONSTRAINT FK_1D03DA06A76ED395');
        $this->addSql('ALTER TABLE service_user DROP CONSTRAINT FK_43D062A5A76ED395');
        $this->addSql('ALTER TABLE service_provider_user DROP CONSTRAINT FK_B8A40FB7A76ED395');
        $this->addSql('ALTER TABLE generic_order DROP CONSTRAINT FK_9C152B8A19EB6921');
        $this->addSql('ALTER TABLE shopping_cart DROP CONSTRAINT FK_72AAD4F6A76ED395');
        $this->addSql('ALTER TABLE condominium_project_user DROP CONSTRAINT FK_8397DF9DA76ED395');
        $this->addSql('ALTER TABLE district DROP CONSTRAINT FK_31C154878BAC62AF');
        $this->addSql('ALTER TABLE condominium_user DROP CONSTRAINT FK_C857CA22FE823105');
        $this->addSql('ALTER TABLE news_condominium DROP CONSTRAINT FK_CB6A896DFE823105');
        $this->addSql('ALTER TABLE feedback DROP CONSTRAINT FK_D2294458FE823105');
        $this->addSql('ALTER TABLE service_condominium DROP CONSTRAINT FK_2EC5198EFE823105');
        $this->addSql('ALTER TABLE unit DROP CONSTRAINT FK_DCBB0C53FE823105');
        $this->addSql('ALTER TABLE unit_type DROP CONSTRAINT FK_18B673FE823105');
        $this->addSql('ALTER TABLE news_condominium DROP CONSTRAINT FK_CB6A896DBF396750');
        $this->addSql('ALTER TABLE news_platform DROP CONSTRAINT FK_706C739ABF396750');
        $this->addSql('ALTER TABLE news_service DROP CONSTRAINT FK_B6798634BF396750');
        $this->addSql('ALTER TABLE city DROP CONSTRAINT FK_2D5B0234F92F3E70');
        $this->addSql('ALTER TABLE shop_item DROP CONSTRAINT FK_DEE9C365EE45BDBF');
        $this->addSql('ALTER TABLE condominium DROP CONSTRAINT FK_E25F3F22B08FA272');
        $this->addSql('ALTER TABLE issue_comment DROP CONSTRAINT FK_318C178D5E7AA58C');
        $this->addSql('ALTER TABLE unit DROP CONSTRAINT FK_DCBB0C53D48E7AED');
        $this->addSql('ALTER TABLE service_condominium DROP CONSTRAINT FK_2EC5198EED5CA9E6');
        $this->addSql('ALTER TABLE service_user DROP CONSTRAINT FK_43D062A5ED5CA9E6');
        $this->addSql('ALTER TABLE service_availability DROP CONSTRAINT FK_E01BD75AED5CA9E6');
        $this->addSql('ALTER TABLE news_service DROP CONSTRAINT FK_B6798634ED5CA9E6');
        $this->addSql('ALTER TABLE service_unavailability DROP CONSTRAINT FK_C664B67ED5CA9E6');
        $this->addSql('ALTER TABLE generic_order DROP CONSTRAINT FK_9C152B8AED5CA9E6');
        $this->addSql('ALTER TABLE shop_item DROP CONSTRAINT FK_DEE9C365ED5CA9E6');
        $this->addSql('ALTER TABLE shopping_cart DROP CONSTRAINT FK_72AAD4F6ED5CA9E6');
        $this->addSql('ALTER TABLE service DROP CONSTRAINT FK_E19D9AD2C6C98E06');
        $this->addSql('ALTER TABLE service_provider_user DROP CONSTRAINT FK_B8A40FB7C6C98E06');
        $this->addSql('ALTER TABLE issue DROP CONSTRAINT FK_12AD233EF8BD700D');
        $this->addSql('ALTER TABLE resident DROP CONSTRAINT FK_1D03DA06F8BD700D');
        $this->addSql('ALTER TABLE generic_order DROP CONSTRAINT FK_9C152B8AF8BD700D');
        $this->addSql('ALTER TABLE unit DROP CONSTRAINT FK_DCBB0C53C54C8C93');
        $this->addSql('ALTER TABLE shopping_cart_item DROP CONSTRAINT FK_E59A1DF4115C1274');
        $this->addSql('ALTER TABLE generic_order DROP CONSTRAINT FK_9C152B8A45F80CD');
        $this->addSql('ALTER TABLE shopping_cart_shopping_cart_item DROP CONSTRAINT FK_F577858145F80CD');
        $this->addSql('ALTER TABLE shopping_cart_shopping_cart_item DROP CONSTRAINT FK_F57785813B3A089F');
        $this->addSql('ALTER TABLE condominium_project_project_contact DROP CONSTRAINT FK_D18919905E30ACFC');
        $this->addSql('ALTER TABLE condominium_project_user DROP CONSTRAINT FK_8397DF9D5E30ACFC');
        $this->addSql('ALTER TABLE project_unit DROP CONSTRAINT FK_E52AC44B166D1F9C');
        $this->addSql('ALTER TABLE project_unit_status DROP CONSTRAINT FK_24967911166D1F9C');
        $this->addSql('ALTER TABLE project_unit_type DROP CONSTRAINT FK_1124F3A4166D1F9C');
        $this->addSql('ALTER TABLE condominium_project_project_contact DROP CONSTRAINT FK_D1891990FF075E78');
        $this->addSql('ALTER TABLE project_unit_project_contact DROP CONSTRAINT FK_71E7D04AFF075E78');
        $this->addSql('ALTER TABLE project_unit_project_contact DROP CONSTRAINT FK_71E7D04A6EA37C68');
        $this->addSql('ALTER TABLE project_unit DROP CONSTRAINT FK_E52AC44B6BF700BD');
        $this->addSql('ALTER TABLE project_unit DROP CONSTRAINT FK_E52AC44BC54C8C93');
        $this->addSql('DROP SEQUENCE fos_user_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE city_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE condominium_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE news_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE country_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE database_file_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE district_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE feedback_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE issue_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE issue_comment_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE landlord_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE resident_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE service_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE service_availability_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE service_provider_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE service_unavailability_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE unit_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE unit_type_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE generic_order_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE shop_item_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE shopping_cart_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE shopping_cart_item_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE condominium_project_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE project_contact_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE project_unit_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE project_unit_status_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE project_unit_type_id_seq CASCADE');
        $this->addSql('DROP TABLE fos_user');
        $this->addSql('DROP TABLE city');
        $this->addSql('DROP TABLE condominium');
        $this->addSql('DROP TABLE condominium_user');
        $this->addSql('DROP TABLE news');
        $this->addSql('DROP TABLE news_condominium');
        $this->addSql('DROP TABLE country');
        $this->addSql('DROP TABLE database_file');
        $this->addSql('DROP TABLE district');
        $this->addSql('DROP TABLE feedback');
        $this->addSql('DROP TABLE issue');
        $this->addSql('DROP TABLE issue_comment');
        $this->addSql('DROP TABLE landlord');
        $this->addSql('DROP TABLE news_platform');
        $this->addSql('DROP TABLE resident');
        $this->addSql('DROP TABLE service');
        $this->addSql('DROP TABLE service_condominium');
        $this->addSql('DROP TABLE service_user');
        $this->addSql('DROP TABLE service_availability');
        $this->addSql('DROP TABLE news_service');
        $this->addSql('DROP TABLE service_provider');
        $this->addSql('DROP TABLE service_provider_user');
        $this->addSql('DROP TABLE service_unavailability');
        $this->addSql('DROP TABLE unit');
        $this->addSql('DROP TABLE unit_type');
        $this->addSql('DROP TABLE generic_order');
        $this->addSql('DROP TABLE shop_item');
        $this->addSql('DROP TABLE shopping_cart');
        $this->addSql('DROP TABLE shopping_cart_shopping_cart_item');
        $this->addSql('DROP TABLE shopping_cart_item');
        $this->addSql('DROP TABLE condominium_project');
        $this->addSql('DROP TABLE condominium_project_project_contact');
        $this->addSql('DROP TABLE condominium_project_user');
        $this->addSql('DROP TABLE project_contact');
        $this->addSql('DROP TABLE project_unit');
        $this->addSql('DROP TABLE project_unit_project_contact');
        $this->addSql('DROP TABLE project_unit_status');
        $this->addSql('DROP TABLE project_unit_type');
    }
}