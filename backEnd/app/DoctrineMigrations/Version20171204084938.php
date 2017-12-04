<?php declare(strict_types = 1);

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20171204084938 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SEQUENCE company_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE message_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE contact_external_account_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE contact_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE message_service_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE sintez_user_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE company (id INT NOT NULL, name VARCHAR(255) NOT NULL, api_token VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE message (id INT NOT NULL, contact_id INT DEFAULT NULL, message_service_type VARCHAR(255) CHECK(message_service_type IN (\'Sms\', \'Email\', \'Phone\', \'Pop-up\', \'Whatsapp\', \'Telegram\', \'Web chat\', \'Undefined\', \'Skype\', \'Instagram_direct\', \'Vkontakte\', \'Facebook\')) NOT NULL, sender_app_type VARCHAR(255) CHECK(sender_app_type IN (\'Web panel\', \'App\', \'Distribution\', \'Trigger\', \'Undefined\', \'API\')) NOT NULL, direction_type VARCHAR(255) CHECK(direction_type IN (\'In\', \'Out\', \'System\')) NOT NULL, body TEXT NOT NULL, contact_read BOOLEAN NOT NULL, operator_read BOOLEAN NOT NULL, create_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, gateway_status_type VARCHAR(255) CHECK(gateway_status_type IN (\'Created\', \'Sent\', \'Delivered\', \'Not delivered\', \'They say\', \'Did not answer\')) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_B6BD307FE7A1254A ON message (contact_id)');
        $this->addSql('COMMENT ON COLUMN message.message_service_type IS \'(DC2Type:MessageServiceType)\'');
        $this->addSql('COMMENT ON COLUMN message.sender_app_type IS \'(DC2Type:MessageSenderAppType)\'');
        $this->addSql('COMMENT ON COLUMN message.direction_type IS \'(DC2Type:MessageDirectionType)\'');
        $this->addSql('COMMENT ON COLUMN message.gateway_status_type IS \'(DC2Type:MessageGatewayStatusType)\'');
        $this->addSql('CREATE TABLE contact_external_account (id INT NOT NULL, company_id INT NOT NULL, contact_id INT DEFAULT NULL, message_service_type VARCHAR(255) CHECK(message_service_type IN (\'Sms\', \'Email\', \'Phone\', \'Pop-up\', \'Whatsapp\', \'Telegram\', \'Web chat\', \'Undefined\', \'Skype\', \'Instagram_direct\', \'Vkontakte\', \'Facebook\')) NOT NULL, main_account_id VARCHAR(255) NOT NULL, additional_account_id TEXT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_656E8573979B1AD6 ON contact_external_account (company_id)');
        $this->addSql('CREATE INDEX IDX_656E8573E7A1254A ON contact_external_account (contact_id)');
        $this->addSql('COMMENT ON COLUMN contact_external_account.message_service_type IS \'(DC2Type:MessageServiceType)\'');
        $this->addSql('COMMENT ON COLUMN contact_external_account.additional_account_id IS \'(DC2Type:array)\'');
        $this->addSql('CREATE TABLE contact (id INT NOT NULL, company INT NOT NULL, presence VARCHAR(255) CHECK(presence IN (\'ONLINE\', \'IDLE\', \'OFFLINE\')) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_4C62E6384FBF094F ON contact (company)');
        $this->addSql('COMMENT ON COLUMN contact.presence IS \'(DC2Type:ContactPresenceType)\'');
        $this->addSql('CREATE TABLE message_service (id INT NOT NULL, company INT NOT NULL, type VARCHAR(255) CHECK(type IN (\'Sms\', \'Email\', \'Phone\', \'Pop-up\', \'Whatsapp\', \'Telegram\', \'Web chat\', \'Undefined\', \'Skype\', \'Instagram_direct\', \'Vkontakte\', \'Facebook\')) NOT NULL, gatewayType VARCHAR(255) CHECK(gatewayType IN (\'Smsprofi\', \'Alloka_phone\', \'Carrot_email\', \'Whatsup\', \'Telegram\', \'CarrotQuest_web_chat\', \'CarrotQuest_popup\', \'Skype\', \'Vkontakte\', \'Facebook\', \'Instagram_direct\')) NOT NULL, connectionData JSON NOT NULL, main_identifier VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_7115A0DE4FBF094F ON message_service (company)');
        $this->addSql('COMMENT ON COLUMN message_service.type IS \'(DC2Type:MessageServiceType)\'');
        $this->addSql('COMMENT ON COLUMN message_service.gatewayType IS \'(DC2Type:MessageServiceGatewayType)\'');
        $this->addSql('COMMENT ON COLUMN message_service.connectionData IS \'(DC2Type:json_array)\'');
        $this->addSql('CREATE TABLE sintez_user (id INT NOT NULL, company INT NOT NULL, apiToken VARCHAR(255) NOT NULL, phone VARCHAR(255) NOT NULL, avatar_micro VARCHAR(255) DEFAULT NULL, avatar_small VARCHAR(255) DEFAULT NULL, avatar_medium VARCHAR(255) DEFAULT NULL, avatar_large VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_F8AD7352E22488D7 ON sintez_user (apiToken)');
        $this->addSql('CREATE INDEX IDX_F8AD73524FBF094F ON sintez_user (company)');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307FE7A1254A FOREIGN KEY (contact_id) REFERENCES contact (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE contact_external_account ADD CONSTRAINT FK_656E8573979B1AD6 FOREIGN KEY (company_id) REFERENCES company (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE contact_external_account ADD CONSTRAINT FK_656E8573E7A1254A FOREIGN KEY (contact_id) REFERENCES contact (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE contact ADD CONSTRAINT FK_4C62E6384FBF094F FOREIGN KEY (company) REFERENCES company (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE message_service ADD CONSTRAINT FK_7115A0DE4FBF094F FOREIGN KEY (company) REFERENCES company (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE sintez_user ADD CONSTRAINT FK_F8AD73524FBF094F FOREIGN KEY (company) REFERENCES company (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE contact_external_account DROP CONSTRAINT FK_656E8573979B1AD6');
        $this->addSql('ALTER TABLE contact DROP CONSTRAINT FK_4C62E6384FBF094F');
        $this->addSql('ALTER TABLE message_service DROP CONSTRAINT FK_7115A0DE4FBF094F');
        $this->addSql('ALTER TABLE sintez_user DROP CONSTRAINT FK_F8AD73524FBF094F');
        $this->addSql('ALTER TABLE message DROP CONSTRAINT FK_B6BD307FE7A1254A');
        $this->addSql('ALTER TABLE contact_external_account DROP CONSTRAINT FK_656E8573E7A1254A');
        $this->addSql('DROP SEQUENCE company_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE message_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE contact_external_account_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE contact_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE message_service_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE sintez_user_id_seq CASCADE');
        $this->addSql('DROP TABLE company');
        $this->addSql('DROP TABLE message');
        $this->addSql('DROP TABLE contact_external_account');
        $this->addSql('DROP TABLE contact');
        $this->addSql('DROP TABLE message_service');
        $this->addSql('DROP TABLE sintez_user');
    }
}
