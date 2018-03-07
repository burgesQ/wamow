<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180307154353 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE continent (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_6CC70C7C5E237E06 (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE certification (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_6C3C6D755E237E06 (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_mission (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, mission_id INT NOT NULL, thread_id INT DEFAULT NULL, status INT NOT NULL, creation_date DATETIME NOT NULL, update_date DATETIME NOT NULL, score INT DEFAULT NULL, score_details LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:json_array)\', interested_at DATETIME DEFAULT NULL, note LONGTEXT NOT NULL, id_for_contractor INT DEFAULT NULL, INDEX IDX_C86AEC36A76ED395 (user_id), INDEX IDX_C86AEC36BE6CAE90 (mission_id), UNIQUE INDEX UNIQ_C86AEC36E2904019 (thread_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE company_size (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_B4AD3E845E237E06 (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE mission (id INT AUTO_INCREMENT NOT NULL, title_id INT DEFAULT NULL, address_id INT DEFAULT NULL, contact INT DEFAULT NULL, company_id INT NOT NULL, professional_expertise_id INT DEFAULT NULL, business_practice_id INT DEFAULT NULL, company_size_id INT DEFAULT NULL, work_experience_id INT DEFAULT NULL, currency_id INT DEFAULT NULL, resume LONGTEXT DEFAULT NULL, confidentiality TINYINT(1) NOT NULL, status SMALLINT NOT NULL, creation_date DATETIME NOT NULL, update_date DATETIME NOT NULL, telecommuting TINYINT(1) DEFAULT NULL, budget INT DEFAULT NULL, original_budget INT DEFAULT NULL, beginning DATETIME DEFAULT NULL, ending DATETIME DEFAULT NULL, application_ending DATETIME DEFAULT NULL, number_step SMALLINT NOT NULL, status_generator INT NOT NULL, price INT DEFAULT NULL, original_price INT DEFAULT NULL, first_name VARCHAR(255) DEFAULT NULL, last_name VARCHAR(255) DEFAULT NULL, share TINYINT(1) DEFAULT NULL, reference TINYINT(1) DEFAULT NULL, scoring_history LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:json_array)\', next_update_scoring DATE DEFAULT NULL, public_id VARCHAR(255) DEFAULT NULL, on_draft TINYINT(1) NOT NULL, nb_ongoing INT NOT NULL, context LONGTEXT DEFAULT NULL, stack LONGTEXT DEFAULT NULL, objective LONGTEXT DEFAULT NULL, UNIQUE INDEX UNIQ_9067F23CB5B48B91 (public_id), INDEX IDX_9067F23CA9F87BD (title_id), UNIQUE INDEX UNIQ_9067F23CF5B7AF75 (address_id), INDEX IDX_9067F23C4C62E638 (contact), INDEX IDX_9067F23C979B1AD6 (company_id), INDEX IDX_9067F23CF682496B (professional_expertise_id), INDEX IDX_9067F23C4F17E149 (business_practice_id), INDEX IDX_9067F23C69DFB2F0 (company_size_id), INDEX IDX_9067F23C6347713 (work_experience_id), INDEX IDX_9067F23C38248176 (currency_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE mission_language (mission_id INT NOT NULL, language_id INT NOT NULL, INDEX IDX_DC27700CBE6CAE90 (mission_id), INDEX IDX_DC27700C82F1BAF4 (language_id), PRIMARY KEY(mission_id, language_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE mission_mission_kind (mission_id INT NOT NULL, mission_kind_id INT NOT NULL, INDEX IDX_C5EC5D72BE6CAE90 (mission_id), INDEX IDX_C5EC5D72A15CB8E4 (mission_kind_id), PRIMARY KEY(mission_id, mission_kind_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE mission_continent (mission_id INT NOT NULL, continent_id INT NOT NULL, INDEX IDX_DE72FB55BE6CAE90 (mission_id), INDEX IDX_DE72FB55921F4C77 (continent_id), PRIMARY KEY(mission_id, continent_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE mission_inspector (mission_id INT NOT NULL, inspector_id INT NOT NULL, INDEX IDX_C068A6A2BE6CAE90 (mission_id), INDEX IDX_C068A6A2D0E3F35F (inspector_id), PRIMARY KEY(mission_id, inspector_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE mission_certification (mission_id INT NOT NULL, certification_id INT NOT NULL, INDEX IDX_BD5060C9BE6CAE90 (mission_id), INDEX IDX_BD5060C9CB47068A (certification_id), PRIMARY KEY(mission_id, certification_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE mission_title (id INT AUTO_INCREMENT NOT NULL, category VARCHAR(255) NOT NULL, title VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_BBA027562B36786B (title), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE mission_kind (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_128610D65E237E06 (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_work_experience (id INT AUTO_INCREMENT NOT NULL, work_experience_id INT DEFAULT NULL, user_id INT DEFAULT NULL, currency_id INT DEFAULT NULL, cumuled_month INT NOT NULL, daily_fees INT NOT NULL, peremption TINYINT(1) NOT NULL, INDEX IDX_C2F005F6347713 (work_experience_id), INDEX IDX_C2F005FA76ED395 (user_id), INDEX IDX_C2F005F38248176 (currency_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_work_experience_company_size (user_work_experience_id INT NOT NULL, company_size_id INT NOT NULL, INDEX IDX_CF5425F6AA7091E8 (user_work_experience_id), INDEX IDX_CF5425F669DFB2F0 (company_size_id), PRIMARY KEY(user_work_experience_id, company_size_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_work_experience_continent (user_work_experience_id INT NOT NULL, continent_id INT NOT NULL, INDEX IDX_8EB26A2AAA7091E8 (user_work_experience_id), INDEX IDX_8EB26A2A921F4C77 (continent_id), PRIMARY KEY(user_work_experience_id, continent_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE professional_expertise (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_97D996605E237E06 (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE work_experience (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_1EF36CD05E237E06 (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE work_experience_business_practice (work_experience_id INT NOT NULL, business_practice_id INT NOT NULL, INDEX IDX_648314BB6347713 (work_experience_id), INDEX IDX_648314BB4F17E149 (business_practice_id), PRIMARY KEY(work_experience_id, business_practice_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE work_experience_professional_expertise (work_experience_id INT NOT NULL, professional_expertise_id INT NOT NULL, INDEX IDX_FA96BEF6347713 (work_experience_id), INDEX IDX_FA96BEFF682496B (professional_expertise_id), PRIMARY KEY(work_experience_id, professional_expertise_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE work_experience_mission_kind (work_experience_id INT NOT NULL, mission_kind_id INT NOT NULL, INDEX IDX_E523F1A66347713 (work_experience_id), INDEX IDX_E523F1A6A15CB8E4 (mission_kind_id), PRIMARY KEY(work_experience_id, mission_kind_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE work_experience_mission_title (work_experience_id INT NOT NULL, mission_title_id INT NOT NULL, INDEX IDX_EB52F38B6347713 (work_experience_id), INDEX IDX_EB52F38B2638604 (mission_title_id), PRIMARY KEY(work_experience_id, mission_title_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE business_practice (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_77DC3AD85E237E06 (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE step (id INT AUTO_INCREMENT NOT NULL, mission_id INT NOT NULL, position SMALLINT NOT NULL, nb_max_user SMALLINT NOT NULL, realloc_user SMALLINT NOT NULL, creation_date DATETIME NOT NULL, update_date DATETIME DEFAULT NULL, start DATETIME DEFAULT NULL, end DATETIME DEFAULT NULL, status SMALLINT NOT NULL, anonymous_mode SMALLINT NOT NULL, INDEX IDX_43B9FE3CBE6CAE90 (mission_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE contact (id INT AUTO_INCREMENT NOT NULL, address VARCHAR(255) NOT NULL, content VARCHAR(255) NOT NULL, date DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE preregister (id INT AUTO_INCREMENT NOT NULL, phone_id INT DEFAULT NULL, email VARCHAR(255) NOT NULL, firstname VARCHAR(255) NOT NULL, lastname VARCHAR(255) NOT NULL, company VARCHAR(255) NOT NULL, country VARCHAR(255) NOT NULL, comment LONGTEXT DEFAULT NULL, UNIQUE INDEX UNIQ_4B36ED2C3B7323CB (phone_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE upload (id INT AUTO_INCREMENT NOT NULL, uploadDate DATETIME NOT NULL, name VARCHAR(255) DEFAULT NULL, type VARCHAR(255) DEFAULT NULL, format LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', path VARCHAR(255) DEFAULT NULL, kind SMALLINT NOT NULL, download_name VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE profile_picture (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, uploadDate DATETIME NOT NULL, name VARCHAR(255) DEFAULT NULL, type VARCHAR(255) DEFAULT NULL, format LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', path VARCHAR(255) DEFAULT NULL, kind SMALLINT NOT NULL, download_name VARCHAR(255) DEFAULT NULL, INDEX IDX_C5659115A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE prefix_number (id INT AUTO_INCREMENT NOT NULL, prefix VARCHAR(255) NOT NULL, country VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE upload_resume (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, user_mission_id INT DEFAULT NULL, uploadDate DATETIME NOT NULL, name VARCHAR(255) DEFAULT NULL, type VARCHAR(255) DEFAULT NULL, format LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', path VARCHAR(255) DEFAULT NULL, kind SMALLINT NOT NULL, download_name VARCHAR(255) DEFAULT NULL, content VARCHAR(255) DEFAULT NULL, INDEX IDX_EDE08018A76ED395 (user_id), INDEX IDX_EDE08018190B687C (user_mission_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE language (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_D4DB71B55E237E06 (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE config (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, value VARCHAR(1000) NOT NULL, creation_date DATETIME NOT NULL, update_date DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_D48A2F7C5E237E06 (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE phone_number (id INT AUTO_INCREMENT NOT NULL, prefix_id INT DEFAULT NULL, number VARCHAR(255) NOT NULL, creation_date DATETIME NOT NULL, update_date DATETIME DEFAULT NULL, INDEX IDX_6B01BC5B5C554FFE (prefix_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE proposal (id INT AUTO_INCREMENT NOT NULL, thread_id INT DEFAULT NULL, mission_id INT DEFAULT NULL, uploadDate DATETIME NOT NULL, name VARCHAR(255) DEFAULT NULL, type VARCHAR(255) DEFAULT NULL, format LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', path VARCHAR(255) DEFAULT NULL, kind SMALLINT NOT NULL, download_name VARCHAR(255) DEFAULT NULL, content VARCHAR(255) DEFAULT NULL, INDEX IDX_BFE59472E2904019 (thread_id), INDEX IDX_BFE59472BE6CAE90 (mission_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE address (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, street VARCHAR(255) DEFAULT NULL, street2 VARCHAR(255) DEFAULT NULL, city VARCHAR(255) DEFAULT NULL, zipcode VARCHAR(255) DEFAULT NULL, state VARCHAR(255) DEFAULT NULL, country VARCHAR(255) DEFAULT NULL, name VARCHAR(255) DEFAULT NULL, label VARCHAR(255) DEFAULT NULL, creation_date DATETIME NOT NULL, update_date DATETIME NOT NULL, INDEX IDX_D4E6F81A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, phone_id INT DEFAULT NULL, company_id INT DEFAULT NULL, username VARCHAR(180) NOT NULL, username_canonical VARCHAR(180) NOT NULL, email VARCHAR(180) NOT NULL, email_canonical VARCHAR(180) NOT NULL, enabled TINYINT(1) NOT NULL, salt VARCHAR(255) DEFAULT NULL, password VARCHAR(255) NOT NULL, last_login DATETIME DEFAULT NULL, confirmation_token VARCHAR(180) DEFAULT NULL, password_requested_at DATETIME DEFAULT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', linkedin_id VARCHAR(255) DEFAULT NULL, linkedin_access_token VARCHAR(255) DEFAULT NULL, linkedin_data LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:json_array)\', status SMALLINT NOT NULL, confidentiality TINYINT(1) NOT NULL, first_name VARCHAR(255) DEFAULT NULL, last_name VARCHAR(255) DEFAULT NULL, daily_fees_min BIGINT DEFAULT NULL, daily_fees_max BIGINT DEFAULT NULL, creation_date DATETIME NOT NULL, update_date DATETIME DEFAULT NULL, newsletter TINYINT(1) NOT NULL, give_up_count INT NOT NULL, secretMail LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', user_resume LONGTEXT DEFAULT NULL, email_emergency VARCHAR(255) DEFAULT NULL, scoring_bonus INT NOT NULL, remote_work TINYINT(1) NOT NULL, public_id VARCHAR(255) DEFAULT NULL, notification TINYINT(1) NOT NULL, plan_subscribed_at DATETIME DEFAULT NULL, plan_expires_at DATETIME DEFAULT NULL, plan_type VARCHAR(255) DEFAULT NULL, plan_payment_amount INT DEFAULT NULL, plan_payment_provider VARCHAR(255) DEFAULT NULL, user_agreement TINYINT(1) NOT NULL, siret VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_8D93D64992FC23A8 (username_canonical), UNIQUE INDEX UNIQ_8D93D649A0D96FBF (email_canonical), UNIQUE INDEX UNIQ_8D93D649C05FB297 (confirmation_token), UNIQUE INDEX UNIQ_8D93D649B5B48B91 (public_id), UNIQUE INDEX UNIQ_8D93D6493B7323CB (phone_id), INDEX IDX_8D93D649979B1AD6 (company_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_language (user_id INT NOT NULL, language_id INT NOT NULL, INDEX IDX_345695B5A76ED395 (user_id), INDEX IDX_345695B582F1BAF4 (language_id), PRIMARY KEY(user_id, language_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_professional_expertise (user_id INT NOT NULL, professional_expertise_id INT NOT NULL, INDEX IDX_20A4CE16A76ED395 (user_id), INDEX IDX_20A4CE16F682496B (professional_expertise_id), PRIMARY KEY(user_id, professional_expertise_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_mission_kind (user_id INT NOT NULL, mission_kind_id INT NOT NULL, INDEX IDX_924A6CD4A76ED395 (user_id), INDEX IDX_924A6CD4A15CB8E4 (mission_kind_id), PRIMARY KEY(user_id, mission_kind_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_business_practice (user_id INT NOT NULL, business_practice_id INT NOT NULL, INDEX IDX_8FC381F3A76ED395 (user_id), INDEX IDX_8FC381F34F17E149 (business_practice_id), PRIMARY KEY(user_id, business_practice_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_certification (user_id INT NOT NULL, certification_id INT NOT NULL, INDEX IDX_82B2C025A76ED395 (user_id), INDEX IDX_82B2C025CB47068A (certification_id), PRIMARY KEY(user_id, certification_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE inspector (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_72DD518B5E237E06 (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE company (id INT AUTO_INCREMENT NOT NULL, business_practice_id INT NOT NULL, address_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, creation_date DATETIME NOT NULL, update_date DATETIME DEFAULT NULL, logo VARCHAR(255) NOT NULL, status INT NOT NULL, resume LONGTEXT DEFAULT NULL, UNIQUE INDEX UNIQ_4FBF094F5E237E06 (name), INDEX IDX_4FBF094F4F17E149 (business_practice_id), UNIQUE INDEX UNIQ_4FBF094FF5B7AF75 (address_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE notification (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, creation_date DATETIME NOT NULL, status SMALLINT NOT NULL, seen TINYINT(1) NOT NULL, message_id VARCHAR(255) NOT NULL, message_params LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:array)\', INDEX IDX_BF5476CAA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE message_metadata (id INT AUTO_INCREMENT NOT NULL, message_id INT DEFAULT NULL, participant_id INT DEFAULT NULL, is_read TINYINT(1) NOT NULL, INDEX IDX_4632F005537A1329 (message_id), INDEX IDX_4632F0059D1C3019 (participant_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE thread (id INT AUTO_INCREMENT NOT NULL, mission_id INT DEFAULT NULL, user_mission_id INT DEFAULT NULL, subject VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, is_spam TINYINT(1) NOT NULL, reply LONGTEXT DEFAULT NULL, INDEX IDX_31204C83BE6CAE90 (mission_id), UNIQUE INDEX UNIQ_31204C83190B687C (user_mission_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE thread_metadata (id INT AUTO_INCREMENT NOT NULL, thread_id INT DEFAULT NULL, participant_id INT DEFAULT NULL, is_deleted TINYINT(1) NOT NULL, last_participant_message_date DATETIME DEFAULT NULL, last_message_date DATETIME DEFAULT NULL, INDEX IDX_40A577C8E2904019 (thread_id), INDEX IDX_40A577C89D1C3019 (participant_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE message (id INT AUTO_INCREMENT NOT NULL, thread_id INT DEFAULT NULL, sender_id INT DEFAULT NULL, body LONGTEXT NOT NULL, created_at DATETIME NOT NULL, user_mission_proposal_id INT DEFAULT NULL, INDEX IDX_B6BD307FE2904019 (thread_id), INDEX IDX_B6BD307FF624B39D (sender_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE article (id INT AUTO_INCREMENT NOT NULL, newsletter_id INT DEFAULT NULL, url VARCHAR(255) NOT NULL, pre_title VARCHAR(255) NOT NULL, title VARCHAR(255) NOT NULL, content LONGTEXT NOT NULL, creation_date DATETIME NOT NULL, update_date DATETIME NOT NULL, post_date DATETIME NOT NULL, published_date DATETIME NOT NULL, posted_by VARCHAR(255) NOT NULL, written_by VARCHAR(255) NOT NULL, category VARCHAR(255) NOT NULL, introduction LONGTEXT NOT NULL, time INT NOT NULL, url_cover VARCHAR(255) NOT NULL, INDEX IDX_23A0E6622DB1917 (newsletter_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE newsletter (id INT AUTO_INCREMENT NOT NULL, pre_title VARCHAR(255) NOT NULL, title VARCHAR(255) NOT NULL, creation_date DATETIME NOT NULL, update_date DATETIME NOT NULL, published_date DATETIME NOT NULL, number DOUBLE PRECISION NOT NULL, url_cover VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE comment (id INT AUTO_INCREMENT NOT NULL, article_id INT DEFAULT NULL, post_date DATETIME NOT NULL, email_author VARCHAR(255) NOT NULL, content LONGTEXT NOT NULL, status INT NOT NULL, first_name_author VARCHAR(255) NOT NULL, last_name_author VARCHAR(255) NOT NULL, INDEX IDX_9474526C7294869C (article_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE lexik_currency (id INT AUTO_INCREMENT NOT NULL, code VARCHAR(3) NOT NULL, rate NUMERIC(10, 4) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE user_mission ADD CONSTRAINT FK_C86AEC36A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE user_mission ADD CONSTRAINT FK_C86AEC36BE6CAE90 FOREIGN KEY (mission_id) REFERENCES mission (id)');
        $this->addSql('ALTER TABLE user_mission ADD CONSTRAINT FK_C86AEC36E2904019 FOREIGN KEY (thread_id) REFERENCES thread (id)');
        $this->addSql('ALTER TABLE mission ADD CONSTRAINT FK_9067F23CA9F87BD FOREIGN KEY (title_id) REFERENCES mission_title (id)');
        $this->addSql('ALTER TABLE mission ADD CONSTRAINT FK_9067F23CF5B7AF75 FOREIGN KEY (address_id) REFERENCES address (id)');
        $this->addSql('ALTER TABLE mission ADD CONSTRAINT FK_9067F23C4C62E638 FOREIGN KEY (contact) REFERENCES user (id)');
        $this->addSql('ALTER TABLE mission ADD CONSTRAINT FK_9067F23C979B1AD6 FOREIGN KEY (company_id) REFERENCES company (id)');
        $this->addSql('ALTER TABLE mission ADD CONSTRAINT FK_9067F23CF682496B FOREIGN KEY (professional_expertise_id) REFERENCES professional_expertise (id)');
        $this->addSql('ALTER TABLE mission ADD CONSTRAINT FK_9067F23C4F17E149 FOREIGN KEY (business_practice_id) REFERENCES business_practice (id)');
        $this->addSql('ALTER TABLE mission ADD CONSTRAINT FK_9067F23C69DFB2F0 FOREIGN KEY (company_size_id) REFERENCES company_size (id)');
        $this->addSql('ALTER TABLE mission ADD CONSTRAINT FK_9067F23C6347713 FOREIGN KEY (work_experience_id) REFERENCES work_experience (id)');
        $this->addSql('ALTER TABLE mission ADD CONSTRAINT FK_9067F23C38248176 FOREIGN KEY (currency_id) REFERENCES lexik_currency (id)');
        $this->addSql('ALTER TABLE mission_language ADD CONSTRAINT FK_DC27700CBE6CAE90 FOREIGN KEY (mission_id) REFERENCES mission (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE mission_language ADD CONSTRAINT FK_DC27700C82F1BAF4 FOREIGN KEY (language_id) REFERENCES language (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE mission_mission_kind ADD CONSTRAINT FK_C5EC5D72BE6CAE90 FOREIGN KEY (mission_id) REFERENCES mission (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE mission_mission_kind ADD CONSTRAINT FK_C5EC5D72A15CB8E4 FOREIGN KEY (mission_kind_id) REFERENCES mission_kind (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE mission_continent ADD CONSTRAINT FK_DE72FB55BE6CAE90 FOREIGN KEY (mission_id) REFERENCES mission (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE mission_continent ADD CONSTRAINT FK_DE72FB55921F4C77 FOREIGN KEY (continent_id) REFERENCES continent (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE mission_inspector ADD CONSTRAINT FK_C068A6A2BE6CAE90 FOREIGN KEY (mission_id) REFERENCES mission (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE mission_inspector ADD CONSTRAINT FK_C068A6A2D0E3F35F FOREIGN KEY (inspector_id) REFERENCES inspector (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE mission_certification ADD CONSTRAINT FK_BD5060C9BE6CAE90 FOREIGN KEY (mission_id) REFERENCES mission (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE mission_certification ADD CONSTRAINT FK_BD5060C9CB47068A FOREIGN KEY (certification_id) REFERENCES certification (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_work_experience ADD CONSTRAINT FK_C2F005F6347713 FOREIGN KEY (work_experience_id) REFERENCES work_experience (id)');
        $this->addSql('ALTER TABLE user_work_experience ADD CONSTRAINT FK_C2F005FA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE user_work_experience ADD CONSTRAINT FK_C2F005F38248176 FOREIGN KEY (currency_id) REFERENCES lexik_currency (id)');
        $this->addSql('ALTER TABLE user_work_experience_company_size ADD CONSTRAINT FK_CF5425F6AA7091E8 FOREIGN KEY (user_work_experience_id) REFERENCES user_work_experience (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_work_experience_company_size ADD CONSTRAINT FK_CF5425F669DFB2F0 FOREIGN KEY (company_size_id) REFERENCES company_size (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_work_experience_continent ADD CONSTRAINT FK_8EB26A2AAA7091E8 FOREIGN KEY (user_work_experience_id) REFERENCES user_work_experience (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_work_experience_continent ADD CONSTRAINT FK_8EB26A2A921F4C77 FOREIGN KEY (continent_id) REFERENCES continent (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE work_experience_business_practice ADD CONSTRAINT FK_648314BB6347713 FOREIGN KEY (work_experience_id) REFERENCES work_experience (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE work_experience_business_practice ADD CONSTRAINT FK_648314BB4F17E149 FOREIGN KEY (business_practice_id) REFERENCES business_practice (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE work_experience_professional_expertise ADD CONSTRAINT FK_FA96BEF6347713 FOREIGN KEY (work_experience_id) REFERENCES work_experience (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE work_experience_professional_expertise ADD CONSTRAINT FK_FA96BEFF682496B FOREIGN KEY (professional_expertise_id) REFERENCES professional_expertise (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE work_experience_mission_kind ADD CONSTRAINT FK_E523F1A66347713 FOREIGN KEY (work_experience_id) REFERENCES work_experience (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE work_experience_mission_kind ADD CONSTRAINT FK_E523F1A6A15CB8E4 FOREIGN KEY (mission_kind_id) REFERENCES mission_kind (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE work_experience_mission_title ADD CONSTRAINT FK_EB52F38B6347713 FOREIGN KEY (work_experience_id) REFERENCES work_experience (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE work_experience_mission_title ADD CONSTRAINT FK_EB52F38B2638604 FOREIGN KEY (mission_title_id) REFERENCES mission_title (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE step ADD CONSTRAINT FK_43B9FE3CBE6CAE90 FOREIGN KEY (mission_id) REFERENCES mission (id)');
        $this->addSql('ALTER TABLE preregister ADD CONSTRAINT FK_4B36ED2C3B7323CB FOREIGN KEY (phone_id) REFERENCES phone_number (id)');
        $this->addSql('ALTER TABLE profile_picture ADD CONSTRAINT FK_C5659115A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE upload_resume ADD CONSTRAINT FK_EDE08018A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE upload_resume ADD CONSTRAINT FK_EDE08018190B687C FOREIGN KEY (user_mission_id) REFERENCES user_mission (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE phone_number ADD CONSTRAINT FK_6B01BC5B5C554FFE FOREIGN KEY (prefix_id) REFERENCES prefix_number (id)');
        $this->addSql('ALTER TABLE proposal ADD CONSTRAINT FK_BFE59472E2904019 FOREIGN KEY (thread_id) REFERENCES thread (id)');
        $this->addSql('ALTER TABLE proposal ADD CONSTRAINT FK_BFE59472BE6CAE90 FOREIGN KEY (mission_id) REFERENCES mission (id)');
        $this->addSql('ALTER TABLE address ADD CONSTRAINT FK_D4E6F81A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D6493B7323CB FOREIGN KEY (phone_id) REFERENCES phone_number (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649979B1AD6 FOREIGN KEY (company_id) REFERENCES company (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE user_language ADD CONSTRAINT FK_345695B5A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_language ADD CONSTRAINT FK_345695B582F1BAF4 FOREIGN KEY (language_id) REFERENCES language (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_professional_expertise ADD CONSTRAINT FK_20A4CE16A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_professional_expertise ADD CONSTRAINT FK_20A4CE16F682496B FOREIGN KEY (professional_expertise_id) REFERENCES professional_expertise (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_mission_kind ADD CONSTRAINT FK_924A6CD4A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_mission_kind ADD CONSTRAINT FK_924A6CD4A15CB8E4 FOREIGN KEY (mission_kind_id) REFERENCES mission_kind (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_business_practice ADD CONSTRAINT FK_8FC381F3A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_business_practice ADD CONSTRAINT FK_8FC381F34F17E149 FOREIGN KEY (business_practice_id) REFERENCES business_practice (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_certification ADD CONSTRAINT FK_82B2C025A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_certification ADD CONSTRAINT FK_82B2C025CB47068A FOREIGN KEY (certification_id) REFERENCES certification (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE company ADD CONSTRAINT FK_4FBF094F4F17E149 FOREIGN KEY (business_practice_id) REFERENCES business_practice (id)');
        $this->addSql('ALTER TABLE company ADD CONSTRAINT FK_4FBF094FF5B7AF75 FOREIGN KEY (address_id) REFERENCES address (id)');
        $this->addSql('ALTER TABLE notification ADD CONSTRAINT FK_BF5476CAA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE message_metadata ADD CONSTRAINT FK_4632F005537A1329 FOREIGN KEY (message_id) REFERENCES message (id)');
        $this->addSql('ALTER TABLE message_metadata ADD CONSTRAINT FK_4632F0059D1C3019 FOREIGN KEY (participant_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE thread ADD CONSTRAINT FK_31204C83BE6CAE90 FOREIGN KEY (mission_id) REFERENCES mission (id)');
        $this->addSql('ALTER TABLE thread ADD CONSTRAINT FK_31204C83190B687C FOREIGN KEY (user_mission_id) REFERENCES user_mission (id)');
        $this->addSql('ALTER TABLE thread_metadata ADD CONSTRAINT FK_40A577C8E2904019 FOREIGN KEY (thread_id) REFERENCES thread (id)');
        $this->addSql('ALTER TABLE thread_metadata ADD CONSTRAINT FK_40A577C89D1C3019 FOREIGN KEY (participant_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307FE2904019 FOREIGN KEY (thread_id) REFERENCES thread (id)');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307FF624B39D FOREIGN KEY (sender_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE article ADD CONSTRAINT FK_23A0E6622DB1917 FOREIGN KEY (newsletter_id) REFERENCES newsletter (id)');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526C7294869C FOREIGN KEY (article_id) REFERENCES article (id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE mission_continent DROP FOREIGN KEY FK_DE72FB55921F4C77');
        $this->addSql('ALTER TABLE user_work_experience_continent DROP FOREIGN KEY FK_8EB26A2A921F4C77');
        $this->addSql('ALTER TABLE mission_certification DROP FOREIGN KEY FK_BD5060C9CB47068A');
        $this->addSql('ALTER TABLE user_certification DROP FOREIGN KEY FK_82B2C025CB47068A');
        $this->addSql('ALTER TABLE upload_resume DROP FOREIGN KEY FK_EDE08018190B687C');
        $this->addSql('ALTER TABLE thread DROP FOREIGN KEY FK_31204C83190B687C');
        $this->addSql('ALTER TABLE mission DROP FOREIGN KEY FK_9067F23C69DFB2F0');
        $this->addSql('ALTER TABLE user_work_experience_company_size DROP FOREIGN KEY FK_CF5425F669DFB2F0');
        $this->addSql('ALTER TABLE user_mission DROP FOREIGN KEY FK_C86AEC36BE6CAE90');
        $this->addSql('ALTER TABLE mission_language DROP FOREIGN KEY FK_DC27700CBE6CAE90');
        $this->addSql('ALTER TABLE mission_mission_kind DROP FOREIGN KEY FK_C5EC5D72BE6CAE90');
        $this->addSql('ALTER TABLE mission_continent DROP FOREIGN KEY FK_DE72FB55BE6CAE90');
        $this->addSql('ALTER TABLE mission_inspector DROP FOREIGN KEY FK_C068A6A2BE6CAE90');
        $this->addSql('ALTER TABLE mission_certification DROP FOREIGN KEY FK_BD5060C9BE6CAE90');
        $this->addSql('ALTER TABLE step DROP FOREIGN KEY FK_43B9FE3CBE6CAE90');
        $this->addSql('ALTER TABLE proposal DROP FOREIGN KEY FK_BFE59472BE6CAE90');
        $this->addSql('ALTER TABLE thread DROP FOREIGN KEY FK_31204C83BE6CAE90');
        $this->addSql('ALTER TABLE mission DROP FOREIGN KEY FK_9067F23CA9F87BD');
        $this->addSql('ALTER TABLE work_experience_mission_title DROP FOREIGN KEY FK_EB52F38B2638604');
        $this->addSql('ALTER TABLE mission_mission_kind DROP FOREIGN KEY FK_C5EC5D72A15CB8E4');
        $this->addSql('ALTER TABLE work_experience_mission_kind DROP FOREIGN KEY FK_E523F1A6A15CB8E4');
        $this->addSql('ALTER TABLE user_mission_kind DROP FOREIGN KEY FK_924A6CD4A15CB8E4');
        $this->addSql('ALTER TABLE user_work_experience_company_size DROP FOREIGN KEY FK_CF5425F6AA7091E8');
        $this->addSql('ALTER TABLE user_work_experience_continent DROP FOREIGN KEY FK_8EB26A2AAA7091E8');
        $this->addSql('ALTER TABLE mission DROP FOREIGN KEY FK_9067F23CF682496B');
        $this->addSql('ALTER TABLE work_experience_professional_expertise DROP FOREIGN KEY FK_FA96BEFF682496B');
        $this->addSql('ALTER TABLE user_professional_expertise DROP FOREIGN KEY FK_20A4CE16F682496B');
        $this->addSql('ALTER TABLE mission DROP FOREIGN KEY FK_9067F23C6347713');
        $this->addSql('ALTER TABLE user_work_experience DROP FOREIGN KEY FK_C2F005F6347713');
        $this->addSql('ALTER TABLE work_experience_business_practice DROP FOREIGN KEY FK_648314BB6347713');
        $this->addSql('ALTER TABLE work_experience_professional_expertise DROP FOREIGN KEY FK_FA96BEF6347713');
        $this->addSql('ALTER TABLE work_experience_mission_kind DROP FOREIGN KEY FK_E523F1A66347713');
        $this->addSql('ALTER TABLE work_experience_mission_title DROP FOREIGN KEY FK_EB52F38B6347713');
        $this->addSql('ALTER TABLE mission DROP FOREIGN KEY FK_9067F23C4F17E149');
        $this->addSql('ALTER TABLE work_experience_business_practice DROP FOREIGN KEY FK_648314BB4F17E149');
        $this->addSql('ALTER TABLE user_business_practice DROP FOREIGN KEY FK_8FC381F34F17E149');
        $this->addSql('ALTER TABLE company DROP FOREIGN KEY FK_4FBF094F4F17E149');
        $this->addSql('ALTER TABLE phone_number DROP FOREIGN KEY FK_6B01BC5B5C554FFE');
        $this->addSql('ALTER TABLE mission_language DROP FOREIGN KEY FK_DC27700C82F1BAF4');
        $this->addSql('ALTER TABLE user_language DROP FOREIGN KEY FK_345695B582F1BAF4');
        $this->addSql('ALTER TABLE preregister DROP FOREIGN KEY FK_4B36ED2C3B7323CB');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D6493B7323CB');
        $this->addSql('ALTER TABLE mission DROP FOREIGN KEY FK_9067F23CF5B7AF75');
        $this->addSql('ALTER TABLE company DROP FOREIGN KEY FK_4FBF094FF5B7AF75');
        $this->addSql('ALTER TABLE user_mission DROP FOREIGN KEY FK_C86AEC36A76ED395');
        $this->addSql('ALTER TABLE mission DROP FOREIGN KEY FK_9067F23C4C62E638');
        $this->addSql('ALTER TABLE user_work_experience DROP FOREIGN KEY FK_C2F005FA76ED395');
        $this->addSql('ALTER TABLE profile_picture DROP FOREIGN KEY FK_C5659115A76ED395');
        $this->addSql('ALTER TABLE upload_resume DROP FOREIGN KEY FK_EDE08018A76ED395');
        $this->addSql('ALTER TABLE address DROP FOREIGN KEY FK_D4E6F81A76ED395');
        $this->addSql('ALTER TABLE user_language DROP FOREIGN KEY FK_345695B5A76ED395');
        $this->addSql('ALTER TABLE user_professional_expertise DROP FOREIGN KEY FK_20A4CE16A76ED395');
        $this->addSql('ALTER TABLE user_mission_kind DROP FOREIGN KEY FK_924A6CD4A76ED395');
        $this->addSql('ALTER TABLE user_business_practice DROP FOREIGN KEY FK_8FC381F3A76ED395');
        $this->addSql('ALTER TABLE user_certification DROP FOREIGN KEY FK_82B2C025A76ED395');
        $this->addSql('ALTER TABLE notification DROP FOREIGN KEY FK_BF5476CAA76ED395');
        $this->addSql('ALTER TABLE message_metadata DROP FOREIGN KEY FK_4632F0059D1C3019');
        $this->addSql('ALTER TABLE thread_metadata DROP FOREIGN KEY FK_40A577C89D1C3019');
        $this->addSql('ALTER TABLE message DROP FOREIGN KEY FK_B6BD307FF624B39D');
        $this->addSql('ALTER TABLE mission_inspector DROP FOREIGN KEY FK_C068A6A2D0E3F35F');
        $this->addSql('ALTER TABLE mission DROP FOREIGN KEY FK_9067F23C979B1AD6');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649979B1AD6');
        $this->addSql('ALTER TABLE user_mission DROP FOREIGN KEY FK_C86AEC36E2904019');
        $this->addSql('ALTER TABLE proposal DROP FOREIGN KEY FK_BFE59472E2904019');
        $this->addSql('ALTER TABLE thread_metadata DROP FOREIGN KEY FK_40A577C8E2904019');
        $this->addSql('ALTER TABLE message DROP FOREIGN KEY FK_B6BD307FE2904019');
        $this->addSql('ALTER TABLE message_metadata DROP FOREIGN KEY FK_4632F005537A1329');
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526C7294869C');
        $this->addSql('ALTER TABLE article DROP FOREIGN KEY FK_23A0E6622DB1917');
        $this->addSql('ALTER TABLE mission DROP FOREIGN KEY FK_9067F23C38248176');
        $this->addSql('ALTER TABLE user_work_experience DROP FOREIGN KEY FK_C2F005F38248176');
        $this->addSql('DROP TABLE continent');
        $this->addSql('DROP TABLE certification');
        $this->addSql('DROP TABLE user_mission');
        $this->addSql('DROP TABLE company_size');
        $this->addSql('DROP TABLE mission');
        $this->addSql('DROP TABLE mission_language');
        $this->addSql('DROP TABLE mission_mission_kind');
        $this->addSql('DROP TABLE mission_continent');
        $this->addSql('DROP TABLE mission_inspector');
        $this->addSql('DROP TABLE mission_certification');
        $this->addSql('DROP TABLE mission_title');
        $this->addSql('DROP TABLE mission_kind');
        $this->addSql('DROP TABLE user_work_experience');
        $this->addSql('DROP TABLE user_work_experience_company_size');
        $this->addSql('DROP TABLE user_work_experience_continent');
        $this->addSql('DROP TABLE professional_expertise');
        $this->addSql('DROP TABLE work_experience');
        $this->addSql('DROP TABLE work_experience_business_practice');
        $this->addSql('DROP TABLE work_experience_professional_expertise');
        $this->addSql('DROP TABLE work_experience_mission_kind');
        $this->addSql('DROP TABLE work_experience_mission_title');
        $this->addSql('DROP TABLE business_practice');
        $this->addSql('DROP TABLE step');
        $this->addSql('DROP TABLE contact');
        $this->addSql('DROP TABLE preregister');
        $this->addSql('DROP TABLE upload');
        $this->addSql('DROP TABLE profile_picture');
        $this->addSql('DROP TABLE prefix_number');
        $this->addSql('DROP TABLE upload_resume');
        $this->addSql('DROP TABLE language');
        $this->addSql('DROP TABLE config');
        $this->addSql('DROP TABLE phone_number');
        $this->addSql('DROP TABLE proposal');
        $this->addSql('DROP TABLE address');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE user_language');
        $this->addSql('DROP TABLE user_professional_expertise');
        $this->addSql('DROP TABLE user_mission_kind');
        $this->addSql('DROP TABLE user_business_practice');
        $this->addSql('DROP TABLE user_certification');
        $this->addSql('DROP TABLE inspector');
        $this->addSql('DROP TABLE company');
        $this->addSql('DROP TABLE notification');
        $this->addSql('DROP TABLE message_metadata');
        $this->addSql('DROP TABLE thread');
        $this->addSql('DROP TABLE thread_metadata');
        $this->addSql('DROP TABLE message');
        $this->addSql('DROP TABLE article');
        $this->addSql('DROP TABLE newsletter');
        $this->addSql('DROP TABLE comment');
        $this->addSql('DROP TABLE lexik_currency');
    }
}
