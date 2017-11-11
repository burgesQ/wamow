<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20171111143405 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE work_experience_mission_title (work_experience_id INT NOT NULL, mission_title_id INT NOT NULL, INDEX IDX_EB52F38B6347713 (work_experience_id), INDEX IDX_EB52F38B2638604 (mission_title_id), PRIMARY KEY(work_experience_id, mission_title_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE work_experience_mission_title ADD CONSTRAINT FK_EB52F38B6347713 FOREIGN KEY (work_experience_id) REFERENCES work_experience (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE work_experience_mission_title ADD CONSTRAINT FK_EB52F38B2638604 FOREIGN KEY (mission_title_id) REFERENCES mission_title (id) ON DELETE CASCADE');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE work_experience_mission_title');
    }
}
