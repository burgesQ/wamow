<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170629083125 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE work_experience_business_practice (work_experience_id INT NOT NULL, business_practice_id INT NOT NULL, INDEX IDX_648314BB6347713 (work_experience_id), INDEX IDX_648314BB4F17E149 (business_practice_id), PRIMARY KEY(work_experience_id, business_practice_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE work_experience_professional_expertise (work_experience_id INT NOT NULL, professional_expertise_id INT NOT NULL, INDEX IDX_FA96BEF6347713 (work_experience_id), INDEX IDX_FA96BEFF682496B (professional_expertise_id), PRIMARY KEY(work_experience_id, professional_expertise_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE work_experience_mission_kind (work_experience_id INT NOT NULL, mission_kind_id INT NOT NULL, INDEX IDX_E523F1A66347713 (work_experience_id), INDEX IDX_E523F1A6A15CB8E4 (mission_kind_id), PRIMARY KEY(work_experience_id, mission_kind_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE work_experience_business_practice ADD CONSTRAINT FK_648314BB6347713 FOREIGN KEY (work_experience_id) REFERENCES work_experience (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE work_experience_business_practice ADD CONSTRAINT FK_648314BB4F17E149 FOREIGN KEY (business_practice_id) REFERENCES business_practice (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE work_experience_professional_expertise ADD CONSTRAINT FK_FA96BEF6347713 FOREIGN KEY (work_experience_id) REFERENCES work_experience (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE work_experience_professional_expertise ADD CONSTRAINT FK_FA96BEFF682496B FOREIGN KEY (professional_expertise_id) REFERENCES professional_expertise (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE work_experience_mission_kind ADD CONSTRAINT FK_E523F1A66347713 FOREIGN KEY (work_experience_id) REFERENCES work_experience (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE work_experience_mission_kind ADD CONSTRAINT FK_E523F1A6A15CB8E4 FOREIGN KEY (mission_kind_id) REFERENCES mission_kind (id) ON DELETE CASCADE');
        $this->addSql('DROP TABLE business_practice_work_experience');
        $this->addSql('DROP TABLE mission_kind_work_experience');
        $this->addSql('DROP TABLE professional_expertise_work_experience');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE business_practice_work_experience (business_practice_id INT NOT NULL, work_experience_id INT NOT NULL, INDEX IDX_663D22AA4F17E149 (business_practice_id), INDEX IDX_663D22AA6347713 (work_experience_id), PRIMARY KEY(business_practice_id, work_experience_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE mission_kind_work_experience (mission_kind_id INT NOT NULL, work_experience_id INT NOT NULL, INDEX IDX_4F95EFC2A15CB8E4 (mission_kind_id), INDEX IDX_4F95EFC26347713 (work_experience_id), PRIMARY KEY(mission_kind_id, work_experience_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE professional_expertise_work_experience (professional_expertise_id INT NOT NULL, work_experience_id INT NOT NULL, INDEX IDX_1146E317F682496B (professional_expertise_id), INDEX IDX_1146E3176347713 (work_experience_id), PRIMARY KEY(professional_expertise_id, work_experience_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE business_practice_work_experience ADD CONSTRAINT FK_663D22AA4F17E149 FOREIGN KEY (business_practice_id) REFERENCES business_practice (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE business_practice_work_experience ADD CONSTRAINT FK_663D22AA6347713 FOREIGN KEY (work_experience_id) REFERENCES work_experience (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE mission_kind_work_experience ADD CONSTRAINT FK_4F95EFC26347713 FOREIGN KEY (work_experience_id) REFERENCES work_experience (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE mission_kind_work_experience ADD CONSTRAINT FK_4F95EFC2A15CB8E4 FOREIGN KEY (mission_kind_id) REFERENCES mission_kind (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE professional_expertise_work_experience ADD CONSTRAINT FK_1146E3176347713 FOREIGN KEY (work_experience_id) REFERENCES work_experience (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE professional_expertise_work_experience ADD CONSTRAINT FK_1146E317F682496B FOREIGN KEY (professional_expertise_id) REFERENCES professional_expertise (id) ON DELETE CASCADE');
        $this->addSql('DROP TABLE work_experience_business_practice');
        $this->addSql('DROP TABLE work_experience_professional_expertise');
        $this->addSql('DROP TABLE work_experience_mission_kind');
    }
}
