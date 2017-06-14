<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170602154107 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE mission ADD company_size_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE mission ADD CONSTRAINT FK_9067F23C69DFB2F0 FOREIGN KEY (company_size_id) REFERENCES company_size (id)');
        $this->addSql('CREATE INDEX IDX_9067F23C69DFB2F0 ON mission (company_size_id)');
        $this->addSql('ALTER TABLE company DROP size');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE company ADD size INT NOT NULL');
        $this->addSql('ALTER TABLE mission DROP FOREIGN KEY FK_9067F23C69DFB2F0');
        $this->addSql('DROP INDEX IDX_9067F23C69DFB2F0 ON mission');
        $this->addSql('ALTER TABLE mission DROP company_size_id');
    }
}
