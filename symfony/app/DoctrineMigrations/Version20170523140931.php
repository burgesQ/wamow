<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170523140931 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE booking DROP FOREIGN KEY FK_E00CEDDEA40A2C8');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649A40A2C8');
        $this->addSql('ALTER TABLE mission_tag DROP FOREIGN KEY FK_98EC20FABAD26311');
        $this->addSql('CREATE TABLE experience_shaping_company_size (experience_shaping_id INT NOT NULL, company_size_id INT NOT NULL, INDEX IDX_B2430E9A30CFFEC5 (experience_shaping_id), INDEX IDX_B2430E9A69DFB2F0 (company_size_id), PRIMARY KEY(experience_shaping_id, company_size_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE experience_shaping_continent (experience_shaping_id INT NOT NULL, continent_id INT NOT NULL, INDEX IDX_991011FC30CFFEC5 (experience_shaping_id), INDEX IDX_991011FC921F4C77 (continent_id), PRIMARY KEY(experience_shaping_id, continent_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE company_size (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_B4AD3E845E237E06 (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE certification (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_6C3C6D755E237E06 (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE mission_continent (mission_id INT NOT NULL, continent_id INT NOT NULL, INDEX IDX_DE72FB55BE6CAE90 (mission_id), INDEX IDX_DE72FB55921F4C77 (continent_id), PRIMARY KEY(mission_id, continent_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE mission_inspector (mission_id INT NOT NULL, inspector_id INT NOT NULL, INDEX IDX_C068A6A2BE6CAE90 (mission_id), INDEX IDX_C068A6A2D0E3F35F (inspector_id), PRIMARY KEY(mission_id, inspector_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE mission_certification (mission_id INT NOT NULL, certification_id INT NOT NULL, INDEX IDX_BD5060C9BE6CAE90 (mission_id), INDEX IDX_BD5060C9CB47068A (certification_id), PRIMARY KEY(mission_id, certification_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE continent (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_6CC70C7C5E237E06 (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE preregister (id INT AUTO_INCREMENT NOT NULL, phone_id INT NOT NULL, email VARCHAR(255) NOT NULL, firstname VARCHAR(255) NOT NULL, lastname VARCHAR(255) NOT NULL, company VARCHAR(255) NOT NULL, country VARCHAR(255) NOT NULL, comment LONGTEXT DEFAULT NULL, UNIQUE INDEX UNIQ_4B36ED2C3B7323CB (phone_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE inspector (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_72DD518B5E237E06 (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE experience_shaping_company_size ADD CONSTRAINT FK_B2430E9A30CFFEC5 FOREIGN KEY (experience_shaping_id) REFERENCES experience_shaping (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE experience_shaping_company_size ADD CONSTRAINT FK_B2430E9A69DFB2F0 FOREIGN KEY (company_size_id) REFERENCES company_size (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE experience_shaping_continent ADD CONSTRAINT FK_991011FC30CFFEC5 FOREIGN KEY (experience_shaping_id) REFERENCES experience_shaping (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE experience_shaping_continent ADD CONSTRAINT FK_991011FC921F4C77 FOREIGN KEY (continent_id) REFERENCES continent (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE mission_continent ADD CONSTRAINT FK_DE72FB55BE6CAE90 FOREIGN KEY (mission_id) REFERENCES mission (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE mission_continent ADD CONSTRAINT FK_DE72FB55921F4C77 FOREIGN KEY (continent_id) REFERENCES continent (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE mission_inspector ADD CONSTRAINT FK_C068A6A2BE6CAE90 FOREIGN KEY (mission_id) REFERENCES mission (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE mission_inspector ADD CONSTRAINT FK_C068A6A2D0E3F35F FOREIGN KEY (inspector_id) REFERENCES inspector (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE mission_certification ADD CONSTRAINT FK_BD5060C9BE6CAE90 FOREIGN KEY (mission_id) REFERENCES mission (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE mission_certification ADD CONSTRAINT FK_BD5060C9CB47068A FOREIGN KEY (certification_id) REFERENCES certification (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE preregister ADD CONSTRAINT FK_4B36ED2C3B7323CB FOREIGN KEY (phone_id) REFERENCES phone_number (id)');
        $this->addSql('DROP TABLE booking');
        $this->addSql('DROP TABLE calendar');
        $this->addSql('DROP TABLE mission_tag');
        $this->addSql('DROP TABLE tag');
        $this->addSql('ALTER TABLE experience_shaping DROP FOREIGN KEY FK_41A3CF71DF2A0DE1');
        $this->addSql('DROP INDEX IDX_41A3CF71DF2A0DE1 ON experience_shaping');
        $this->addSql('ALTER TABLE experience_shaping ADD work_experience_id INT DEFAULT NULL, DROP work_title_id, DROP small_company, DROP medium_company, DROP large_company, DROP south_america, DROP north_america, DROP asia, DROP emea');
        $this->addSql('ALTER TABLE experience_shaping ADD CONSTRAINT FK_41A3CF716347713 FOREIGN KEY (work_experience_id) REFERENCES work_experience (id)');
        $this->addSql('CREATE INDEX IDX_41A3CF716347713 ON experience_shaping (work_experience_id)');
        $this->addSql('DROP INDEX UNIQ_9067F23C5F37A13B ON mission');
        $this->addSql('ALTER TABLE mission ADD status_generator INT NOT NULL, ADD price INT DEFAULT NULL, ADD last_name VARCHAR(255) DEFAULT NULL, ADD share TINYINT(1) DEFAULT NULL, ADD reference TINYINT(1) DEFAULT NULL, ADD public_id VARCHAR(255) DEFAULT NULL, ADD on_draft TINYINT(1) NOT NULL, DROP international, DROP token, DROP south_america, DROP north_america, DROP asia, DROP emea, CHANGE business_practice_id business_practice_id INT DEFAULT NULL, CHANGE address_id address_id INT DEFAULT NULL, CHANGE professional_expertise_id professional_expertise_id INT DEFAULT NULL, CHANGE resume resume LONGTEXT DEFAULT NULL, CHANGE update_date update_date DATETIME NOT NULL, CHANGE telecommuting telecommuting TINYINT(1) DEFAULT NULL, CHANGE budget budget INT DEFAULT NULL, CHANGE beginning beginning DATETIME DEFAULT NULL, CHANGE ending ending DATETIME DEFAULT NULL, CHANGE applicationEnding applicationEnding DATETIME DEFAULT NULL, CHANGE image first_name VARCHAR(255) DEFAULT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_9067F23CB5B48B91 ON mission (public_id)');
        $this->addSql('ALTER TABLE user_mission ADD creation_date DATETIME NOT NULL, ADD update_date DATETIME NOT NULL, ADD interested_at DATETIME DEFAULT NULL, ADD note LONGTEXT NOT NULL, DROP creationDate, DROP updateDate');
        $this->addSql('ALTER TABLE upload CHANGE format format LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\'');
        $this->addSql('ALTER TABLE upload_resume DROP FOREIGN KEY FK_EDE08018A76ED395');
        $this->addSql('ALTER TABLE upload_resume CHANGE user_id user_id INT DEFAULT NULL, CHANGE format format LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\'');
        $this->addSql('ALTER TABLE upload_resume ADD CONSTRAINT FK_EDE08018A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE proposal CHANGE format format LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\'');
        $this->addSql('ALTER TABLE address ADD user_id INT DEFAULT NULL, ADD number VARCHAR(255) DEFAULT NULL, CHANGE country country VARCHAR(255) DEFAULT NULL, CHANGE update_date update_date DATETIME NOT NULL');
        $this->addSql('ALTER TABLE address ADD CONSTRAINT FK_D4E6F81A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_D4E6F81A76ED395 ON address (user_id)');
        $this->addSql('ALTER TABLE profile_picture CHANGE format format LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\'');
        $this->addSql('DROP INDEX UNIQ_8D93D649A40A2C8 ON user');
        $this->addSql('ALTER TABLE user ADD linkedin_data LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:array)\', ADD remote_work TINYINT(1) NOT NULL, ADD payment TINYINT(1) NOT NULL, ADD notification TINYINT(1) NOT NULL, DROP calendar_id, DROP password_set, DROP gender, DROP birthdate, DROP nb_load, DROP read_report, CHANGE first_name first_name VARCHAR(255) DEFAULT NULL, CHANGE last_name last_name VARCHAR(255) DEFAULT NULL, CHANGE country public_id VARCHAR(255) DEFAULT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649B5B48B91 ON user (public_id)');
        $this->addSql('ALTER TABLE company ADD address_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE company ADD CONSTRAINT FK_4FBF094FF5B7AF75 FOREIGN KEY (address_id) REFERENCES address (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_4FBF094FF5B7AF75 ON company (address_id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE experience_shaping_company_size DROP FOREIGN KEY FK_B2430E9A69DFB2F0');
        $this->addSql('ALTER TABLE mission_certification DROP FOREIGN KEY FK_BD5060C9CB47068A');
        $this->addSql('ALTER TABLE experience_shaping_continent DROP FOREIGN KEY FK_991011FC921F4C77');
        $this->addSql('ALTER TABLE mission_continent DROP FOREIGN KEY FK_DE72FB55921F4C77');
        $this->addSql('ALTER TABLE mission_inspector DROP FOREIGN KEY FK_C068A6A2D0E3F35F');
        $this->addSql('CREATE TABLE booking (id INT AUTO_INCREMENT NOT NULL, calendar_id INT NOT NULL, title VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci, start DATETIME NOT NULL, end DATETIME NOT NULL, resume LONGTEXT DEFAULT NULL COLLATE utf8_unicode_ci, creation_date DATETIME NOT NULL, update_date DATETIME DEFAULT NULL, status SMALLINT NOT NULL, INDEX IDX_E00CEDDEA40A2C8 (calendar_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE calendar (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE mission_tag (mission_id INT NOT NULL, tag_id INT NOT NULL, INDEX IDX_98EC20FABE6CAE90 (mission_id), INDEX IDX_98EC20FABAD26311 (tag_id), PRIMARY KEY(mission_id, tag_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tag (id INT AUTO_INCREMENT NOT NULL, tag VARCHAR(50) DEFAULT NULL COLLATE utf8_unicode_ci, UNIQUE INDEX UNIQ_389B783389B783 (tag), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE booking ADD CONSTRAINT FK_E00CEDDEA40A2C8 FOREIGN KEY (calendar_id) REFERENCES calendar (id)');
        $this->addSql('ALTER TABLE mission_tag ADD CONSTRAINT FK_98EC20FABAD26311 FOREIGN KEY (tag_id) REFERENCES tag (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE mission_tag ADD CONSTRAINT FK_98EC20FABE6CAE90 FOREIGN KEY (mission_id) REFERENCES mission (id) ON DELETE CASCADE');
        $this->addSql('DROP TABLE experience_shaping_company_size');
        $this->addSql('DROP TABLE experience_shaping_continent');
        $this->addSql('DROP TABLE company_size');
        $this->addSql('DROP TABLE certification');
        $this->addSql('DROP TABLE mission_continent');
        $this->addSql('DROP TABLE mission_inspector');
        $this->addSql('DROP TABLE mission_certification');
        $this->addSql('DROP TABLE continent');
        $this->addSql('DROP TABLE preregister');
        $this->addSql('DROP TABLE inspector');
        $this->addSql('ALTER TABLE address DROP FOREIGN KEY FK_D4E6F81A76ED395');
        $this->addSql('DROP INDEX IDX_D4E6F81A76ED395 ON address');
        $this->addSql('ALTER TABLE address DROP user_id, DROP number, CHANGE country country VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, CHANGE update_date update_date DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE company DROP FOREIGN KEY FK_4FBF094FF5B7AF75');
        $this->addSql('DROP INDEX UNIQ_4FBF094FF5B7AF75 ON company');
        $this->addSql('ALTER TABLE company DROP address_id');
        $this->addSql('ALTER TABLE experience_shaping DROP FOREIGN KEY FK_41A3CF716347713');
        $this->addSql('DROP INDEX IDX_41A3CF716347713 ON experience_shaping');
        $this->addSql('ALTER TABLE experience_shaping ADD work_title_id INT NOT NULL, ADD small_company TINYINT(1) NOT NULL, ADD medium_company TINYINT(1) NOT NULL, ADD large_company TINYINT(1) NOT NULL, ADD south_america TINYINT(1) NOT NULL, ADD north_america TINYINT(1) NOT NULL, ADD asia TINYINT(1) NOT NULL, ADD emea TINYINT(1) NOT NULL, DROP work_experience_id');
        $this->addSql('ALTER TABLE experience_shaping ADD CONSTRAINT FK_41A3CF71DF2A0DE1 FOREIGN KEY (work_title_id) REFERENCES work_experience (id)');
        $this->addSql('CREATE INDEX IDX_41A3CF71DF2A0DE1 ON experience_shaping (work_title_id)');
        $this->addSql('DROP INDEX UNIQ_9067F23CB5B48B91 ON mission');
        $this->addSql('ALTER TABLE mission ADD image VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci, ADD token VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, ADD south_america TINYINT(1) NOT NULL, ADD north_america TINYINT(1) NOT NULL, ADD asia TINYINT(1) NOT NULL, ADD emea TINYINT(1) NOT NULL, DROP status_generator, DROP price, DROP first_name, DROP last_name, DROP share, DROP reference, DROP public_id, CHANGE address_id address_id INT NOT NULL, CHANGE professional_expertise_id professional_expertise_id INT NOT NULL, CHANGE business_practice_id business_practice_id INT NOT NULL, CHANGE resume resume LONGTEXT NOT NULL COLLATE utf8_unicode_ci, CHANGE update_date update_date DATETIME DEFAULT NULL, CHANGE telecommuting telecommuting TINYINT(1) NOT NULL, CHANGE budget budget INT NOT NULL, CHANGE beginning beginning DATETIME NOT NULL, CHANGE ending ending DATETIME NOT NULL, CHANGE applicationEnding applicationEnding DATETIME NOT NULL, CHANGE on_draft international TINYINT(1) NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_9067F23C5F37A13B ON mission (token)');
        $this->addSql('ALTER TABLE profile_picture CHANGE format format VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci');
        $this->addSql('ALTER TABLE proposal CHANGE format format VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci');
        $this->addSql('ALTER TABLE upload CHANGE format format VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci');
        $this->addSql('ALTER TABLE upload_resume DROP FOREIGN KEY FK_EDE08018A76ED395');
        $this->addSql('ALTER TABLE upload_resume CHANGE user_id user_id INT NOT NULL, CHANGE format format VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci');
        $this->addSql('ALTER TABLE upload_resume ADD CONSTRAINT FK_EDE08018A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('DROP INDEX UNIQ_8D93D649B5B48B91 ON user');
        $this->addSql('ALTER TABLE user ADD calendar_id INT NOT NULL, ADD password_set TINYINT(1) NOT NULL, ADD gender SMALLINT DEFAULT NULL, ADD birthdate DATETIME DEFAULT NULL, ADD nb_load SMALLINT NOT NULL, ADD read_report TINYINT(1) NOT NULL, DROP linkedin_data, DROP remote_work, DROP payment, DROP notification, CHANGE first_name first_name VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, CHANGE last_name last_name VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, CHANGE public_id country VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649A40A2C8 FOREIGN KEY (calendar_id) REFERENCES calendar (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649A40A2C8 ON user (calendar_id)');
        $this->addSql('ALTER TABLE user_mission ADD creationDate DATETIME NOT NULL, ADD updateDate DATETIME NOT NULL, DROP creation_date, DROP update_date, DROP interested_at, DROP note');
    }
}
