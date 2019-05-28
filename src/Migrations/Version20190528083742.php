<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190528083742 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE TABLE phone_employee (phone_id INT NOT NULL, employee_id INT NOT NULL, PRIMARY KEY(phone_id, employee_id))');
        $this->addSql('CREATE INDEX IDX_95D866AD3B7323CB ON phone_employee (phone_id)');
        $this->addSql('CREATE INDEX IDX_95D866AD8C03F15C ON phone_employee (employee_id)');
        $this->addSql('ALTER TABLE phone_employee ADD CONSTRAINT FK_95D866AD3B7323CB FOREIGN KEY (phone_id) REFERENCES phone (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE phone_employee ADD CONSTRAINT FK_95D866AD8C03F15C FOREIGN KEY (employee_id) REFERENCES employee (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP TABLE phone_employee');
    }
}
