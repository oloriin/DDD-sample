<?php declare(strict_types = 1);

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20171206042826 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('DROP SEQUENCE user_id_seq CASCADE');
        $this->addSql('CREATE SEQUENCE sample_user_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE sample_user (id INT NOT NULL, company INT NOT NULL, username VARCHAR(180) NOT NULL, username_canonical VARCHAR(180) NOT NULL, email VARCHAR(180) NOT NULL, email_canonical VARCHAR(180) NOT NULL, enabled BOOLEAN NOT NULL, salt VARCHAR(255) DEFAULT NULL, password VARCHAR(255) NOT NULL, last_login TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, confirmation_token VARCHAR(180) DEFAULT NULL, password_requested_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, roles TEXT NOT NULL, apiToken VARCHAR(255) NOT NULL, phone VARCHAR(255) NOT NULL, avatar_micro VARCHAR(255) DEFAULT NULL, avatar_small VARCHAR(255) DEFAULT NULL, avatar_medium VARCHAR(255) DEFAULT NULL, avatar_large VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_31A5F40492FC23A8 ON sample_user (username_canonical)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_31A5F404A0D96FBF ON sample_user (email_canonical)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_31A5F404C05FB297 ON sample_user (confirmation_token)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_31A5F404E22488D7 ON sample_user (apiToken)');
        $this->addSql('CREATE INDEX IDX_31A5F4044FBF094F ON sample_user (company)');
        $this->addSql('COMMENT ON COLUMN sample_user.roles IS \'(DC2Type:array)\'');
        $this->addSql('ALTER TABLE sample_user ADD CONSTRAINT FK_31A5F4044FBF094F FOREIGN KEY (company) REFERENCES company (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('DROP TABLE "user"');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE sample_user_id_seq CASCADE');
        $this->addSql('CREATE SEQUENCE user_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE "user" (id INT NOT NULL, company INT NOT NULL, username VARCHAR(180) NOT NULL, username_canonical VARCHAR(180) NOT NULL, email VARCHAR(180) NOT NULL, email_canonical VARCHAR(180) NOT NULL, enabled BOOLEAN NOT NULL, salt VARCHAR(255) DEFAULT NULL, password VARCHAR(255) NOT NULL, last_login TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, confirmation_token VARCHAR(180) DEFAULT NULL, password_requested_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, roles TEXT NOT NULL, apitoken VARCHAR(255) NOT NULL, phone VARCHAR(255) NOT NULL, avatar_micro VARCHAR(255) DEFAULT NULL, avatar_small VARCHAR(255) DEFAULT NULL, avatar_medium VARCHAR(255) DEFAULT NULL, avatar_large VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX uniq_8d93d649c05fb297 ON "user" (confirmation_token)');
        $this->addSql('CREATE UNIQUE INDEX uniq_8d93d64992fc23a8 ON "user" (username_canonical)');
        $this->addSql('CREATE INDEX idx_8d93d6494fbf094f ON "user" (company)');
        $this->addSql('CREATE UNIQUE INDEX uniq_8d93d649a0d96fbf ON "user" (email_canonical)');
        $this->addSql('CREATE UNIQUE INDEX uniq_8d93d649e22488d7 ON "user" (apitoken)');
        $this->addSql('COMMENT ON COLUMN "user".roles IS \'(DC2Type:array)\'');
        $this->addSql('ALTER TABLE "user" ADD CONSTRAINT fk_8d93d6494fbf094f FOREIGN KEY (company) REFERENCES company (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('DROP TABLE sample_user');
    }
}
