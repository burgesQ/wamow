<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170518113556 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql("
        CREATE TABLE business_practice (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_77DC3AD85E237E06 (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;
        CREATE TABLE step (id INT AUTO_INCREMENT NOT NULL, mission_id INT NOT NULL, position SMALLINT NOT NULL, nb_max_user SMALLINT NOT NULL, realloc_user SMALLINT NOT NULL, creation_date DATETIME NOT NULL, update_date DATETIME DEFAULT NULL, start DATETIME DEFAULT NULL, end DATETIME DEFAULT NULL, status SMALLINT NOT NULL, anonymous_mode SMALLINT NOT NULL, INDEX IDX_43B9FE3CBE6CAE90 (mission_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;
        CREATE TABLE experience_shaping (id INT AUTO_INCREMENT NOT NULL, work_title_id INT NOT NULL, small_company TINYINT(1) NOT NULL, medium_company TINYINT(1) NOT NULL, large_company TINYINT(1) NOT NULL, south_america TINYINT(1) NOT NULL, north_america TINYINT(1) NOT NULL, asia TINYINT(1) NOT NULL, emea TINYINT(1) NOT NULL, cumuled_month INT NOT NULL, daily_fees INT NOT NULL, peremption TINYINT(1) NOT NULL, INDEX IDX_41A3CF71DF2A0DE1 (work_title_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;
        CREATE TABLE professional_expertise (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_97D996605E237E06 (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;
        CREATE TABLE work_experience (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_1EF36CD05E237E06 (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;
        CREATE TABLE mission_kind (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_128610D65E237E06 (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;
        CREATE TABLE mission (id INT AUTO_INCREMENT NOT NULL, address_id INT NOT NULL, contact INT DEFAULT NULL, company_id INT NOT NULL, professional_expertise_id INT NOT NULL, business_practice_id INT NOT NULL, title VARCHAR(255) NOT NULL, resume LONGTEXT NOT NULL, confidentiality TINYINT(1) NOT NULL, status SMALLINT NOT NULL, creation_date DATETIME NOT NULL, update_date DATETIME DEFAULT NULL, telecommuting TINYINT(1) NOT NULL, international TINYINT(1) NOT NULL, budget INT NOT NULL, beginning DATETIME NOT NULL, ending DATETIME NOT NULL, applicationEnding DATETIME NOT NULL, image VARCHAR(255) DEFAULT NULL, number_step SMALLINT NOT NULL, token VARCHAR(255) NOT NULL, south_america TINYINT(1) NOT NULL, north_america TINYINT(1) NOT NULL, asia TINYINT(1) NOT NULL, emea TINYINT(1) NOT NULL, scoring_history LONGTEXT DEFAULT NULL COMMENT '(DC2Type:json_array)', UNIQUE INDEX UNIQ_9067F23C5F37A13B (token), UNIQUE INDEX UNIQ_9067F23CF5B7AF75 (address_id), INDEX IDX_9067F23C4C62E638 (contact), INDEX IDX_9067F23C979B1AD6 (company_id), INDEX IDX_9067F23CF682496B (professional_expertise_id), INDEX IDX_9067F23C4F17E149 (business_practice_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;
        CREATE TABLE mission_language (mission_id INT NOT NULL, language_id INT NOT NULL, INDEX IDX_DC27700CBE6CAE90 (mission_id), INDEX IDX_DC27700C82F1BAF4 (language_id), PRIMARY KEY(mission_id, language_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;
        CREATE TABLE mission_mission_kind (mission_id INT NOT NULL, mission_kind_id INT NOT NULL, INDEX IDX_C5EC5D72BE6CAE90 (mission_id), INDEX IDX_C5EC5D72A15CB8E4 (mission_kind_id), PRIMARY KEY(mission_id, mission_kind_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;
        CREATE TABLE mission_tag (mission_id INT NOT NULL, tag_id INT NOT NULL, INDEX IDX_98EC20FABE6CAE90 (mission_id), INDEX IDX_98EC20FABAD26311 (tag_id), PRIMARY KEY(mission_id, tag_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;
        CREATE TABLE user_mission (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, mission_id INT NOT NULL, thread_id INT DEFAULT NULL, status INT NOT NULL, creationDate DATETIME NOT NULL, updateDate DATETIME NOT NULL, score INT DEFAULT NULL, INDEX IDX_C86AEC36A76ED395 (user_id), INDEX IDX_C86AEC36BE6CAE90 (mission_id), UNIQUE INDEX UNIQ_C86AEC36E2904019 (thread_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;
        CREATE TABLE upload (id INT AUTO_INCREMENT NOT NULL, uploadDate DATETIME NOT NULL, name VARCHAR(255) DEFAULT NULL, type VARCHAR(255) DEFAULT NULL, format VARCHAR(255) DEFAULT NULL, path VARCHAR(255) DEFAULT NULL, kind SMALLINT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;
        CREATE TABLE upload_resume (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, uploadDate DATETIME NOT NULL, name VARCHAR(255) DEFAULT NULL, type VARCHAR(255) DEFAULT NULL, format VARCHAR(255) DEFAULT NULL, path VARCHAR(255) DEFAULT NULL, kind SMALLINT NOT NULL, content VARCHAR(255) DEFAULT NULL, INDEX IDX_EDE08018A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;
        CREATE TABLE proposal (id INT AUTO_INCREMENT NOT NULL, thread_id INT NOT NULL, uploadDate DATETIME NOT NULL, name VARCHAR(255) DEFAULT NULL, type VARCHAR(255) DEFAULT NULL, format VARCHAR(255) DEFAULT NULL, path VARCHAR(255) DEFAULT NULL, kind SMALLINT NOT NULL, content VARCHAR(255) DEFAULT NULL, INDEX IDX_BFE59472E2904019 (thread_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;
        CREATE TABLE address (id INT AUTO_INCREMENT NOT NULL, street VARCHAR(255) DEFAULT NULL, street2 VARCHAR(255) DEFAULT NULL, city VARCHAR(255) DEFAULT NULL, zipcode VARCHAR(255) DEFAULT NULL, state VARCHAR(255) DEFAULT NULL, country VARCHAR(255) NOT NULL, name VARCHAR(255) DEFAULT NULL, label VARCHAR(255) DEFAULT NULL, creation_date DATETIME NOT NULL, update_date DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;
        CREATE TABLE tag (id INT AUTO_INCREMENT NOT NULL, tag VARCHAR(50) DEFAULT NULL, UNIQUE INDEX UNIQ_389B783389B783 (tag), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;
        CREATE TABLE config (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, value VARCHAR(1000) NOT NULL, creation_date DATETIME NOT NULL, update_date DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_D48A2F7C5E237E06 (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;
        CREATE TABLE language (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_D4DB71B55E237E06 (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;
        CREATE TABLE phone_number (id INT AUTO_INCREMENT NOT NULL, prefix_id INT DEFAULT NULL, number VARCHAR(255) NOT NULL, creation_date DATETIME NOT NULL, update_date DATETIME DEFAULT NULL, INDEX IDX_6B01BC5B5C554FFE (prefix_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;
        CREATE TABLE prefix_number (id INT AUTO_INCREMENT NOT NULL, prefix VARCHAR(255) NOT NULL, country VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;
        CREATE TABLE profile_picture (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, uploadDate DATETIME NOT NULL, name VARCHAR(255) DEFAULT NULL, type VARCHAR(255) DEFAULT NULL, format VARCHAR(255) DEFAULT NULL, path VARCHAR(255) DEFAULT NULL, kind SMALLINT NOT NULL, INDEX IDX_C5659115A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;
        CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, phone_id INT DEFAULT NULL, company_id INT DEFAULT NULL, calendar_id INT NOT NULL, username VARCHAR(180) NOT NULL, username_canonical VARCHAR(180) NOT NULL, email VARCHAR(180) NOT NULL, email_canonical VARCHAR(180) NOT NULL, enabled TINYINT(1) NOT NULL, salt VARCHAR(255) DEFAULT NULL, password VARCHAR(255) NOT NULL, last_login DATETIME DEFAULT NULL, confirmation_token VARCHAR(180) DEFAULT NULL, password_requested_at DATETIME DEFAULT NULL, roles LONGTEXT NOT NULL COMMENT '(DC2Type:array)', linkedin_id VARCHAR(255) DEFAULT NULL, linkedin_access_token VARCHAR(255) DEFAULT NULL, status SMALLINT NOT NULL, password_set TINYINT(1) NOT NULL, confidentiality TINYINT(1) NOT NULL, first_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, gender SMALLINT DEFAULT NULL, birthdate DATETIME DEFAULT NULL, daily_fees_min BIGINT DEFAULT NULL, daily_fees_max BIGINT DEFAULT NULL, creation_date DATETIME NOT NULL, update_date DATETIME DEFAULT NULL, country VARCHAR(255) DEFAULT NULL, newsletter TINYINT(1) NOT NULL, give_up_count INT NOT NULL, secretMail LONGTEXT NOT NULL COMMENT '(DC2Type:array)', user_resume LONGTEXT DEFAULT NULL, email_emergency VARCHAR(255) DEFAULT NULL, nb_load SMALLINT NOT NULL, read_report TINYINT(1) NOT NULL, bonus_points INT NOT NULL, UNIQUE INDEX UNIQ_8D93D64992FC23A8 (username_canonical), UNIQUE INDEX UNIQ_8D93D649A0D96FBF (email_canonical), UNIQUE INDEX UNIQ_8D93D649C05FB297 (confirmation_token), UNIQUE INDEX UNIQ_8D93D6493B7323CB (phone_id), INDEX IDX_8D93D649979B1AD6 (company_id), UNIQUE INDEX UNIQ_8D93D649A40A2C8 (calendar_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;
        CREATE TABLE user_language (user_id INT NOT NULL, language_id INT NOT NULL, INDEX IDX_345695B5A76ED395 (user_id), INDEX IDX_345695B582F1BAF4 (language_id), PRIMARY KEY(user_id, language_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;
        CREATE TABLE user_professional_expertise (user_id INT NOT NULL, professional_expertise_id INT NOT NULL, INDEX IDX_20A4CE16A76ED395 (user_id), INDEX IDX_20A4CE16F682496B (professional_expertise_id), PRIMARY KEY(user_id, professional_expertise_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;
        CREATE TABLE user_mission_kind (user_id INT NOT NULL, mission_kind_id INT NOT NULL, INDEX IDX_924A6CD4A76ED395 (user_id), INDEX IDX_924A6CD4A15CB8E4 (mission_kind_id), PRIMARY KEY(user_id, mission_kind_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;
        CREATE TABLE user_business_practice (user_id INT NOT NULL, business_practice_id INT NOT NULL, INDEX IDX_8FC381F3A76ED395 (user_id), INDEX IDX_8FC381F34F17E149 (business_practice_id), PRIMARY KEY(user_id, business_practice_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;
        CREATE TABLE user_work_experience (user_id INT NOT NULL, work_experience_id INT NOT NULL, INDEX IDX_C2F005FA76ED395 (user_id), INDEX IDX_C2F005F6347713 (work_experience_id), PRIMARY KEY(user_id, work_experience_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;
        CREATE TABLE user_experience_shaping (user_id INT NOT NULL, experience_shaping_id INT NOT NULL, INDEX IDX_EDE7298AA76ED395 (user_id), INDEX IDX_EDE7298A30CFFEC5 (experience_shaping_id), PRIMARY KEY(user_id, experience_shaping_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;
        CREATE TABLE company (id INT AUTO_INCREMENT NOT NULL, business_practice_id INT NOT NULL, name VARCHAR(255) NOT NULL, creation_date DATETIME NOT NULL, update_date DATETIME DEFAULT NULL, size INT NOT NULL, logo VARCHAR(255) NOT NULL, status INT NOT NULL, resume LONGTEXT DEFAULT NULL, UNIQUE INDEX UNIQ_4FBF094F5E237E06 (name), INDEX IDX_4FBF094F4F17E149 (business_practice_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;
        CREATE TABLE booking (id INT AUTO_INCREMENT NOT NULL, calendar_id INT NOT NULL, title VARCHAR(255) DEFAULT NULL, start DATETIME NOT NULL, end DATETIME NOT NULL, resume LONGTEXT DEFAULT NULL, creation_date DATETIME NOT NULL, update_date DATETIME DEFAULT NULL, status SMALLINT NOT NULL, INDEX IDX_E00CEDDEA40A2C8 (calendar_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;
        CREATE TABLE calendar (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;
        CREATE TABLE notification (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, creation_date DATETIME NOT NULL, status SMALLINT NOT NULL, seen TINYINT(1) NOT NULL, message_id VARCHAR(255) NOT NULL, message_params LONGTEXT DEFAULT NULL COMMENT '(DC2Type:array)', INDEX IDX_BF5476CAA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;
        CREATE TABLE thread (id INT AUTO_INCREMENT NOT NULL, mission_id INT DEFAULT NULL, user_mission_id INT DEFAULT NULL, subject VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, is_spam TINYINT(1) NOT NULL, reply LONGTEXT DEFAULT NULL, INDEX IDX_31204C83BE6CAE90 (mission_id), UNIQUE INDEX UNIQ_31204C83190B687C (user_mission_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;
        CREATE TABLE message_metadata (id INT AUTO_INCREMENT NOT NULL, message_id INT DEFAULT NULL, participant_id INT DEFAULT NULL, is_read TINYINT(1) NOT NULL, INDEX IDX_4632F005537A1329 (message_id), INDEX IDX_4632F0059D1C3019 (participant_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;
        CREATE TABLE thread_metadata (id INT AUTO_INCREMENT NOT NULL, thread_id INT DEFAULT NULL, participant_id INT DEFAULT NULL, is_deleted TINYINT(1) NOT NULL, last_participant_message_date DATETIME DEFAULT NULL, last_message_date DATETIME DEFAULT NULL, INDEX IDX_40A577C8E2904019 (thread_id), INDEX IDX_40A577C89D1C3019 (participant_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;
        CREATE TABLE message (id INT AUTO_INCREMENT NOT NULL, thread_id INT DEFAULT NULL, sender_id INT DEFAULT NULL, body LONGTEXT NOT NULL, created_at DATETIME NOT NULL, INDEX IDX_B6BD307FE2904019 (thread_id), INDEX IDX_B6BD307FF624B39D (sender_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;
        CREATE TABLE newsletter (id INT AUTO_INCREMENT NOT NULL, pre_title VARCHAR(255) NOT NULL, title VARCHAR(255) NOT NULL, creation_date DATETIME NOT NULL, update_date DATETIME NOT NULL, published_date DATETIME NOT NULL, number INT NOT NULL, url_cover VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;
        CREATE TABLE article (id INT AUTO_INCREMENT NOT NULL, newsletter_id INT DEFAULT NULL, url VARCHAR(255) NOT NULL, pre_title VARCHAR(255) NOT NULL, title VARCHAR(255) NOT NULL, content LONGTEXT NOT NULL, creation_date DATETIME NOT NULL, update_date DATETIME NOT NULL, post_date DATETIME NOT NULL, published_date DATETIME NOT NULL, posted_by VARCHAR(255) NOT NULL, written_by VARCHAR(255) NOT NULL, category VARCHAR(255) NOT NULL, introduction LONGTEXT NOT NULL, time INT NOT NULL, url_cover VARCHAR(255) NOT NULL, INDEX IDX_23A0E6622DB1917 (newsletter_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;
        CREATE TABLE comment (id INT AUTO_INCREMENT NOT NULL, article_id INT DEFAULT NULL, post_date DATETIME NOT NULL, email_author VARCHAR(255) NOT NULL, content LONGTEXT NOT NULL, status INT NOT NULL, first_name_author VARCHAR(255) NOT NULL, last_name_author VARCHAR(255) NOT NULL, INDEX IDX_9474526C7294869C (article_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;
        ALTER TABLE step ADD CONSTRAINT FK_43B9FE3CBE6CAE90 FOREIGN KEY (mission_id) REFERENCES mission (id);
        ALTER TABLE experience_shaping ADD CONSTRAINT FK_41A3CF71DF2A0DE1 FOREIGN KEY (work_title_id) REFERENCES work_experience (id);
        ALTER TABLE mission ADD CONSTRAINT FK_9067F23CF5B7AF75 FOREIGN KEY (address_id) REFERENCES address (id);
        ALTER TABLE mission ADD CONSTRAINT FK_9067F23C4C62E638 FOREIGN KEY (contact) REFERENCES user (id);
        ALTER TABLE mission ADD CONSTRAINT FK_9067F23C979B1AD6 FOREIGN KEY (company_id) REFERENCES company (id);
        ALTER TABLE mission ADD CONSTRAINT FK_9067F23CF682496B FOREIGN KEY (professional_expertise_id) REFERENCES professional_expertise (id);
        ALTER TABLE mission ADD CONSTRAINT FK_9067F23C4F17E149 FOREIGN KEY (business_practice_id) REFERENCES business_practice (id);
        ALTER TABLE mission_language ADD CONSTRAINT FK_DC27700CBE6CAE90 FOREIGN KEY (mission_id) REFERENCES mission (id) ON DELETE CASCADE;
        ALTER TABLE mission_language ADD CONSTRAINT FK_DC27700C82F1BAF4 FOREIGN KEY (language_id) REFERENCES language (id) ON DELETE CASCADE;
        ALTER TABLE mission_mission_kind ADD CONSTRAINT FK_C5EC5D72BE6CAE90 FOREIGN KEY (mission_id) REFERENCES mission (id) ON DELETE CASCADE;
        ALTER TABLE mission_mission_kind ADD CONSTRAINT FK_C5EC5D72A15CB8E4 FOREIGN KEY (mission_kind_id) REFERENCES mission_kind (id) ON DELETE CASCADE;
        ALTER TABLE mission_tag ADD CONSTRAINT FK_98EC20FABE6CAE90 FOREIGN KEY (mission_id) REFERENCES mission (id) ON DELETE CASCADE;
        ALTER TABLE mission_tag ADD CONSTRAINT FK_98EC20FABAD26311 FOREIGN KEY (tag_id) REFERENCES tag (id) ON DELETE CASCADE;
        ALTER TABLE user_mission ADD CONSTRAINT FK_C86AEC36A76ED395 FOREIGN KEY (user_id) REFERENCES user (id);
        ALTER TABLE user_mission ADD CONSTRAINT FK_C86AEC36BE6CAE90 FOREIGN KEY (mission_id) REFERENCES mission (id);
        ALTER TABLE user_mission ADD CONSTRAINT FK_C86AEC36E2904019 FOREIGN KEY (thread_id) REFERENCES thread (id);
        ALTER TABLE upload_resume ADD CONSTRAINT FK_EDE08018A76ED395 FOREIGN KEY (user_id) REFERENCES user (id);
        ALTER TABLE proposal ADD CONSTRAINT FK_BFE59472E2904019 FOREIGN KEY (thread_id) REFERENCES thread (id);
        ALTER TABLE phone_number ADD CONSTRAINT FK_6B01BC5B5C554FFE FOREIGN KEY (prefix_id) REFERENCES prefix_number (id);
        ALTER TABLE profile_picture ADD CONSTRAINT FK_C5659115A76ED395 FOREIGN KEY (user_id) REFERENCES user (id);
        ALTER TABLE user ADD CONSTRAINT FK_8D93D6493B7323CB FOREIGN KEY (phone_id) REFERENCES phone_number (id);
        ALTER TABLE user ADD CONSTRAINT FK_8D93D649979B1AD6 FOREIGN KEY (company_id) REFERENCES company (id) ON DELETE SET NULL;
        ALTER TABLE user ADD CONSTRAINT FK_8D93D649A40A2C8 FOREIGN KEY (calendar_id) REFERENCES calendar (id);
        ALTER TABLE user_language ADD CONSTRAINT FK_345695B5A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE;
        ALTER TABLE user_language ADD CONSTRAINT FK_345695B582F1BAF4 FOREIGN KEY (language_id) REFERENCES language (id) ON DELETE CASCADE;
        ALTER TABLE user_professional_expertise ADD CONSTRAINT FK_20A4CE16A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE;
        ALTER TABLE user_professional_expertise ADD CONSTRAINT FK_20A4CE16F682496B FOREIGN KEY (professional_expertise_id) REFERENCES professional_expertise (id) ON DELETE CASCADE;
        ALTER TABLE user_mission_kind ADD CONSTRAINT FK_924A6CD4A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE;
        ALTER TABLE user_mission_kind ADD CONSTRAINT FK_924A6CD4A15CB8E4 FOREIGN KEY (mission_kind_id) REFERENCES mission_kind (id) ON DELETE CASCADE;
        ALTER TABLE user_business_practice ADD CONSTRAINT FK_8FC381F3A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE;
        ALTER TABLE user_business_practice ADD CONSTRAINT FK_8FC381F34F17E149 FOREIGN KEY (business_practice_id) REFERENCES business_practice (id) ON DELETE CASCADE;
        ALTER TABLE user_work_experience ADD CONSTRAINT FK_C2F005FA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE;
        ALTER TABLE user_work_experience ADD CONSTRAINT FK_C2F005F6347713 FOREIGN KEY (work_experience_id) REFERENCES work_experience (id) ON DELETE CASCADE;
        ALTER TABLE user_experience_shaping ADD CONSTRAINT FK_EDE7298AA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE;
        ALTER TABLE user_experience_shaping ADD CONSTRAINT FK_EDE7298A30CFFEC5 FOREIGN KEY (experience_shaping_id) REFERENCES experience_shaping (id) ON DELETE CASCADE;
        ALTER TABLE company ADD CONSTRAINT FK_4FBF094F4F17E149 FOREIGN KEY (business_practice_id) REFERENCES business_practice (id);
        ALTER TABLE booking ADD CONSTRAINT FK_E00CEDDEA40A2C8 FOREIGN KEY (calendar_id) REFERENCES calendar (id);
        ALTER TABLE notification ADD CONSTRAINT FK_BF5476CAA76ED395 FOREIGN KEY (user_id) REFERENCES user (id);
        ALTER TABLE thread ADD CONSTRAINT FK_31204C83BE6CAE90 FOREIGN KEY (mission_id) REFERENCES mission (id);
        ALTER TABLE thread ADD CONSTRAINT FK_31204C83190B687C FOREIGN KEY (user_mission_id) REFERENCES user_mission (id);
        ALTER TABLE message_metadata ADD CONSTRAINT FK_4632F005537A1329 FOREIGN KEY (message_id) REFERENCES message (id);
        ALTER TABLE message_metadata ADD CONSTRAINT FK_4632F0059D1C3019 FOREIGN KEY (participant_id) REFERENCES user (id);
        ALTER TABLE thread_metadata ADD CONSTRAINT FK_40A577C8E2904019 FOREIGN KEY (thread_id) REFERENCES thread (id);
        ALTER TABLE thread_metadata ADD CONSTRAINT FK_40A577C89D1C3019 FOREIGN KEY (participant_id) REFERENCES user (id);
        ALTER TABLE message ADD CONSTRAINT FK_B6BD307FE2904019 FOREIGN KEY (thread_id) REFERENCES thread (id);
        ALTER TABLE message ADD CONSTRAINT FK_B6BD307FF624B39D FOREIGN KEY (sender_id) REFERENCES user (id);
        ALTER TABLE article ADD CONSTRAINT FK_23A0E6622DB1917 FOREIGN KEY (newsletter_id) REFERENCES newsletter (id);
        ALTER TABLE comment ADD CONSTRAINT FK_9474526C7294869C FOREIGN KEY (article_id) REFERENCES article (id);
        ");
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs

    }
}
