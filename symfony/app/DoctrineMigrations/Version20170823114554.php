<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170823114554 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE mission_title (id INT AUTO_INCREMENT NOT NULL, category VARCHAR(255) NOT NULL, title VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_BBA027562B36786B (title), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE mission ADD title_id INT DEFAULT NULL, DROP title');
        $this->addSql('ALTER TABLE mission ADD CONSTRAINT FK_9067F23CA9F87BD FOREIGN KEY (title_id) REFERENCES mission_title (id)');
        $this->addSql('CREATE INDEX IDX_9067F23CA9F87BD ON mission (title_id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE mission DROP FOREIGN KEY FK_9067F23CA9F87BD');
        $this->addSql('DROP TABLE mission_title');
        $this->addSql('DROP INDEX IDX_9067F23CA9F87BD ON mission');
        $this->addSql('ALTER TABLE mission ADD title VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, DROP title_id');
    }
}
