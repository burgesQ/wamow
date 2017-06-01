<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170531130443 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE user_mission ADD id_for_contractor INT DEFAULT NULL');
        $this->addSql('ALTER TABLE company_size DROP FOREIGN KEY FK_B4AD3E84490AA7FE');
        $this->addSql('DROP INDEX IDX_B4AD3E84490AA7FE ON company_size');
        $this->addSql('ALTER TABLE company_size DROP user_work_experiences_id');
        $this->addSql('ALTER TABLE upload ADD download_name VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE profile_picture ADD download_name VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE proposal ADD download_name VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE upload_resume ADD user_mission_id INT DEFAULT NULL, ADD download_name VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE upload_resume ADD CONSTRAINT FK_EDE08018190B687C FOREIGN KEY (user_mission_id) REFERENCES user_mission (id) ON DELETE SET NULL');
        $this->addSql('CREATE INDEX IDX_EDE08018190B687C ON upload_resume (user_mission_id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE company_size ADD user_work_experiences_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE company_size ADD CONSTRAINT FK_B4AD3E84490AA7FE FOREIGN KEY (user_work_experiences_id) REFERENCES user_work_experience (id)');
        $this->addSql('CREATE INDEX IDX_B4AD3E84490AA7FE ON company_size (user_work_experiences_id)');
        $this->addSql('ALTER TABLE profile_picture DROP download_name');
        $this->addSql('ALTER TABLE proposal DROP download_name');
        $this->addSql('ALTER TABLE upload DROP download_name');
        $this->addSql('ALTER TABLE upload_resume DROP FOREIGN KEY FK_EDE08018190B687C');
        $this->addSql('DROP INDEX IDX_EDE08018190B687C ON upload_resume');
        $this->addSql('ALTER TABLE upload_resume DROP user_mission_id, DROP download_name');
        $this->addSql('ALTER TABLE user_mission DROP id_for_contractor');
    }
}
