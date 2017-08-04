<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170803163337 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE lexik_currency (id INT AUTO_INCREMENT NOT NULL, code VARCHAR(3) NOT NULL, rate NUMERIC(10, 4) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE user_work_experience ADD currency_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user_work_experience ADD CONSTRAINT FK_C2F005F38248176 FOREIGN KEY (currency_id) REFERENCES lexik_currency (id)');
        $this->addSql('CREATE INDEX IDX_C2F005F38248176 ON user_work_experience (currency_id)');
        $this->addSql('ALTER TABLE mission ADD currency_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE mission ADD CONSTRAINT FK_9067F23C38248176 FOREIGN KEY (currency_id) REFERENCES lexik_currency (id)');
        $this->addSql('CREATE INDEX IDX_9067F23C38248176 ON mission (currency_id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE user_work_experience DROP FOREIGN KEY FK_C2F005F38248176');
        $this->addSql('ALTER TABLE mission DROP FOREIGN KEY FK_9067F23C38248176');
        $this->addSql('DROP TABLE lexik_currency');
        $this->addSql('DROP INDEX IDX_9067F23C38248176 ON mission');
        $this->addSql('ALTER TABLE mission DROP currency_id');
        $this->addSql('DROP INDEX IDX_C2F005F38248176 ON user_work_experience');
        $this->addSql('ALTER TABLE user_work_experience DROP currency_id');
    }
}
