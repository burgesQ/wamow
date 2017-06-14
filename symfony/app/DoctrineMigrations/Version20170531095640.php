<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170531095640 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE user_work_experience');
        $this->addSql('ALTER TABLE experience_shaping_company_size DROP FOREIGN KEY FK_B2430E9A30CFFEC5');
        $this->addSql('ALTER TABLE experience_shaping_continent DROP FOREIGN KEY FK_991011FC30CFFEC5');
        $this->addSql('ALTER TABLE user_experience_shaping DROP FOREIGN KEY FK_EDE7298A30CFFEC5');
        $this->addSql('CREATE TABLE user_work_experience (id INT AUTO_INCREMENT NOT NULL, work_experience_id INT DEFAULT NULL, user_id INT DEFAULT NULL, cumuled_month INT NOT NULL, daily_fees INT NOT NULL, peremption TINYINT(1) NOT NULL, INDEX IDX_C2F005F6347713 (work_experience_id), INDEX IDX_C2F005FA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_work_experience_company_size (user_work_experience_id INT NOT NULL, company_size_id INT NOT NULL, INDEX IDX_CF5425F6AA7091E8 (user_work_experience_id), INDEX IDX_CF5425F669DFB2F0 (company_size_id), PRIMARY KEY(user_work_experience_id, company_size_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_work_experience_continent (user_work_experience_id INT NOT NULL, continent_id INT NOT NULL, INDEX IDX_8EB26A2AAA7091E8 (user_work_experience_id), INDEX IDX_8EB26A2A921F4C77 (continent_id), PRIMARY KEY(user_work_experience_id, continent_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE user_work_experience ADD CONSTRAINT FK_C2F005F6347713 FOREIGN KEY (work_experience_id) REFERENCES work_experience (id)');
        $this->addSql('ALTER TABLE user_work_experience ADD CONSTRAINT FK_C2F005FA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE user_work_experience_company_size ADD CONSTRAINT FK_CF5425F6AA7091E8 FOREIGN KEY (user_work_experience_id) REFERENCES user_work_experience (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_work_experience_company_size ADD CONSTRAINT FK_CF5425F669DFB2F0 FOREIGN KEY (company_size_id) REFERENCES company_size (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_work_experience_continent ADD CONSTRAINT FK_8EB26A2AAA7091E8 FOREIGN KEY (user_work_experience_id) REFERENCES user_work_experience (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_work_experience_continent ADD CONSTRAINT FK_8EB26A2A921F4C77 FOREIGN KEY (continent_id) REFERENCES continent (id) ON DELETE CASCADE');
        $this->addSql('DROP TABLE experience_shaping');
        $this->addSql('DROP TABLE experience_shaping_company_size');
        $this->addSql('DROP TABLE experience_shaping_continent');
        $this->addSql('DROP TABLE user_experience_shaping');
        $this->addSql('ALTER TABLE company_size ADD user_work_experiences_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE company_size ADD CONSTRAINT FK_B4AD3E84490AA7FE FOREIGN KEY (user_work_experiences_id) REFERENCES user_work_experience (id)');
        $this->addSql('CREATE INDEX IDX_B4AD3E84490AA7FE ON company_size (user_work_experiences_id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE company_size DROP FOREIGN KEY FK_B4AD3E84490AA7FE');
        $this->addSql('ALTER TABLE user_work_experience_company_size DROP FOREIGN KEY FK_CF5425F6AA7091E8');
        $this->addSql('ALTER TABLE user_work_experience_continent DROP FOREIGN KEY FK_8EB26A2AAA7091E8');
        $this->addSql('CREATE TABLE experience_shaping (id INT AUTO_INCREMENT NOT NULL, work_experience_id INT DEFAULT NULL, cumuled_month INT NOT NULL, daily_fees INT NOT NULL, peremption TINYINT(1) NOT NULL, INDEX IDX_41A3CF716347713 (work_experience_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE experience_shaping_company_size (experience_shaping_id INT NOT NULL, company_size_id INT NOT NULL, INDEX IDX_B2430E9A30CFFEC5 (experience_shaping_id), INDEX IDX_B2430E9A69DFB2F0 (company_size_id), PRIMARY KEY(experience_shaping_id, company_size_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE experience_shaping_continent (experience_shaping_id INT NOT NULL, continent_id INT NOT NULL, INDEX IDX_991011FC30CFFEC5 (experience_shaping_id), INDEX IDX_991011FC921F4C77 (continent_id), PRIMARY KEY(experience_shaping_id, continent_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_experience_shaping (user_id INT NOT NULL, experience_shaping_id INT NOT NULL, INDEX IDX_EDE7298AA76ED395 (user_id), INDEX IDX_EDE7298A30CFFEC5 (experience_shaping_id), PRIMARY KEY(user_id, experience_shaping_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE experience_shaping ADD CONSTRAINT FK_41A3CF716347713 FOREIGN KEY (work_experience_id) REFERENCES work_experience (id)');
        $this->addSql('ALTER TABLE experience_shaping_company_size ADD CONSTRAINT FK_B2430E9A30CFFEC5 FOREIGN KEY (experience_shaping_id) REFERENCES experience_shaping (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE experience_shaping_company_size ADD CONSTRAINT FK_B2430E9A69DFB2F0 FOREIGN KEY (company_size_id) REFERENCES company_size (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE experience_shaping_continent ADD CONSTRAINT FK_991011FC30CFFEC5 FOREIGN KEY (experience_shaping_id) REFERENCES experience_shaping (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE experience_shaping_continent ADD CONSTRAINT FK_991011FC921F4C77 FOREIGN KEY (continent_id) REFERENCES continent (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_experience_shaping ADD CONSTRAINT FK_EDE7298A30CFFEC5 FOREIGN KEY (experience_shaping_id) REFERENCES experience_shaping (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_experience_shaping ADD CONSTRAINT FK_EDE7298AA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('DROP TABLE user_work_experience');
        $this->addSql('DROP TABLE user_work_experience_company_size');
        $this->addSql('DROP TABLE user_work_experience_continent');
        $this->addSql('DROP INDEX IDX_B4AD3E84490AA7FE ON company_size');
        $this->addSql('ALTER TABLE company_size DROP user_work_experiences_id');
    }
}
