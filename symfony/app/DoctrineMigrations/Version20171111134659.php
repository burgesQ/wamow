<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20171111134659 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE work_experience ADD mission_title_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE work_experience ADD CONSTRAINT FK_1EF36CD02638604 FOREIGN KEY (mission_title_id) REFERENCES mission_title (id)');
        $this->addSql('CREATE INDEX IDX_1EF36CD02638604 ON work_experience (mission_title_id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE work_experience DROP FOREIGN KEY FK_1EF36CD02638604');
        $this->addSql('DROP INDEX IDX_1EF36CD02638604 ON work_experience');
        $this->addSql('ALTER TABLE work_experience DROP mission_title_id');
    }
}
