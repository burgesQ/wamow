<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170602102935 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE company ADD company_size_id INT DEFAULT NULL, DROP size');
        $this->addSql('ALTER TABLE company ADD CONSTRAINT FK_4FBF094F69DFB2F0 FOREIGN KEY (company_size_id) REFERENCES company_size (id)');
        $this->addSql('CREATE INDEX IDX_4FBF094F69DFB2F0 ON company (company_size_id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE company DROP FOREIGN KEY FK_4FBF094F69DFB2F0');
        $this->addSql('DROP INDEX IDX_4FBF094F69DFB2F0 ON company');
        $this->addSql('ALTER TABLE company ADD size INT NOT NULL, DROP company_size_id');
    }
}
