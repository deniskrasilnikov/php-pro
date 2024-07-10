<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240711193714 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Migration for web attacks demonstration';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE sql_injection_victim (id INT AUTO_INCREMENT NOT NULL PRIMARY KEY)');
        $this->addSql('CREATE TABLE client (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(100) NOT NULL, age INT NOT NULL, PRIMARY KEY(id))');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE IF EXISTS sql_injection_victim');
        $this->addSql('DROP TABLE IF EXISTS client');
    }
}
