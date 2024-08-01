<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240801134001 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE book_translation (id INT AUTO_INCREMENT NOT NULL, book_id  INT UNSIGNED DEFAULT NULL, name VARCHAR(150) NOT NULL, locale VARCHAR(8) NOT NULL, INDEX IDX_E69E0A1316A2B381 (book_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE book_translation ADD CONSTRAINT FK_E69E0A1316A2B381 FOREIGN KEY (book_id) REFERENCES book (id) ON DELETE CASCADE');
        // todo додати SQL перенесення назв з book до book_translation в базовій локалі (en) ДО ВИДАЛЕННЯ book.name
        $this->addSql('ALTER TABLE book DROP name');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE book ADD name VARCHAR(50) NOT NULL');
        // todo додати SQL перенесення назв в en-локалі з book_translation до book.name ДО ВИДАЛЕННЯ book_translation
        $this->addSql('ALTER TABLE book_translation DROP FOREIGN KEY FK_E69E0A1316A2B381');
        $this->addSql('DROP TABLE book_translation');

    }
}
