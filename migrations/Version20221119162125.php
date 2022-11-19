<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221119162125 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE readlist (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(55) NOT NULL, description LONGTEXT DEFAULT NULL, is_favorite TINYINT(1) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE readlist_book (readlist_id INT NOT NULL, book_id INT NOT NULL, INDEX IDX_445911D1457C67B9 (readlist_id), INDEX IDX_445911D116A2B381 (book_id), PRIMARY KEY(readlist_id, book_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, full_name VARCHAR(50) NOT NULL, pseudo VARCHAR(50) DEFAULT NULL, email VARCHAR(180) NOT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE readlist_book ADD CONSTRAINT FK_445911D1457C67B9 FOREIGN KEY (readlist_id) REFERENCES readlist (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE readlist_book ADD CONSTRAINT FK_445911D116A2B381 FOREIGN KEY (book_id) REFERENCES book (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE book ADD resume LONGTEXT DEFAULT NULL, CHANGE author author VARCHAR(50) DEFAULT NULL, CHANGE create_at create_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE readlist_book DROP FOREIGN KEY FK_445911D1457C67B9');
        $this->addSql('ALTER TABLE readlist_book DROP FOREIGN KEY FK_445911D116A2B381');
        $this->addSql('DROP TABLE readlist');
        $this->addSql('DROP TABLE readlist_book');
        $this->addSql('DROP TABLE user');
        $this->addSql('ALTER TABLE book DROP resume, CHANGE author author VARCHAR(255) DEFAULT NULL, CHANGE create_at create_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
    }
}
