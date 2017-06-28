<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170627093212 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE work_experience_contractor_business_practices (work_experience_id INT NOT NULL, business_practice_id INT NOT NULL, INDEX IDX_3ED85AF86347713 (work_experience_id), INDEX IDX_3ED85AF84F17E149 (business_practice_id), PRIMARY KEY(work_experience_id, business_practice_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE work_experience_contractor_professional_expertises (work_experience_id INT NOT NULL, professional_expertise_id INT NOT NULL, INDEX IDX_9A16932A6347713 (work_experience_id), INDEX IDX_9A16932AF682496B (professional_expertise_id), PRIMARY KEY(work_experience_id, professional_expertise_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE work_experience_contractor_mission_kinds (work_experience_id INT NOT NULL, mission_kind_id INT NOT NULL, INDEX IDX_2E1FC5856347713 (work_experience_id), INDEX IDX_2E1FC585A15CB8E4 (mission_kind_id), PRIMARY KEY(work_experience_id, mission_kind_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE work_experience_advisor_business_practices (work_experience_id INT NOT NULL, business_practice_id INT NOT NULL, INDEX IDX_B0E5BABF6347713 (work_experience_id), INDEX IDX_B0E5BABF4F17E149 (business_practice_id), PRIMARY KEY(work_experience_id, business_practice_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE work_experience_advisor_professional_expertises (work_experience_id INT NOT NULL, professional_expertise_id INT NOT NULL, INDEX IDX_2F09954D6347713 (work_experience_id), INDEX IDX_2F09954DF682496B (professional_expertise_id), PRIMARY KEY(work_experience_id, professional_expertise_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE work_experience_advisor_mission_kinds (work_experience_id INT NOT NULL, mission_kind_id INT NOT NULL, INDEX IDX_A0016FC66347713 (work_experience_id), INDEX IDX_A0016FC6A15CB8E4 (mission_kind_id), PRIMARY KEY(work_experience_id, mission_kind_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE work_experience_contractor_business_practices ADD CONSTRAINT FK_3ED85AF86347713 FOREIGN KEY (work_experience_id) REFERENCES work_experience (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE work_experience_contractor_business_practices ADD CONSTRAINT FK_3ED85AF84F17E149 FOREIGN KEY (business_practice_id) REFERENCES business_practice (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE work_experience_contractor_professional_expertises ADD CONSTRAINT FK_9A16932A6347713 FOREIGN KEY (work_experience_id) REFERENCES work_experience (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE work_experience_contractor_professional_expertises ADD CONSTRAINT FK_9A16932AF682496B FOREIGN KEY (professional_expertise_id) REFERENCES professional_expertise (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE work_experience_contractor_mission_kinds ADD CONSTRAINT FK_2E1FC5856347713 FOREIGN KEY (work_experience_id) REFERENCES work_experience (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE work_experience_contractor_mission_kinds ADD CONSTRAINT FK_2E1FC585A15CB8E4 FOREIGN KEY (mission_kind_id) REFERENCES mission_kind (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE work_experience_advisor_business_practices ADD CONSTRAINT FK_B0E5BABF6347713 FOREIGN KEY (work_experience_id) REFERENCES work_experience (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE work_experience_advisor_business_practices ADD CONSTRAINT FK_B0E5BABF4F17E149 FOREIGN KEY (business_practice_id) REFERENCES business_practice (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE work_experience_advisor_professional_expertises ADD CONSTRAINT FK_2F09954D6347713 FOREIGN KEY (work_experience_id) REFERENCES work_experience (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE work_experience_advisor_professional_expertises ADD CONSTRAINT FK_2F09954DF682496B FOREIGN KEY (professional_expertise_id) REFERENCES professional_expertise (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE work_experience_advisor_mission_kinds ADD CONSTRAINT FK_A0016FC66347713 FOREIGN KEY (work_experience_id) REFERENCES work_experience (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE work_experience_advisor_mission_kinds ADD CONSTRAINT FK_A0016FC6A15CB8E4 FOREIGN KEY (mission_kind_id) REFERENCES mission_kind (id) ON DELETE CASCADE');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE work_experience_contractor_business_practices');
        $this->addSql('DROP TABLE work_experience_contractor_professional_expertises');
        $this->addSql('DROP TABLE work_experience_contractor_mission_kinds');
        $this->addSql('DROP TABLE work_experience_advisor_business_practices');
        $this->addSql('DROP TABLE work_experience_advisor_professional_expertises');
        $this->addSql('DROP TABLE work_experience_advisor_mission_kinds');
    }
}
