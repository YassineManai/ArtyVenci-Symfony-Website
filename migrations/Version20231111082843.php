<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231111082843 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE `show` (id INT AUTO_INCREMENT NOT NULL, theatre_id INT DEFAULT NULL, num_show INT NOT NULL, nbrseat INT NOT NULL, dateshow DATE NOT NULL, INDEX IDX_320ED901C80060CD (theatre_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE theatre_play (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(20) NOT NULL, genre VARCHAR(10) NOT NULL, duration VARCHAR(20) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE `show` ADD CONSTRAINT FK_320ED901C80060CD FOREIGN KEY (theatre_id) REFERENCES theatre_play (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE `show` DROP FOREIGN KEY FK_320ED901C80060CD');
        $this->addSql('DROP TABLE `show`');
        $this->addSql('DROP TABLE theatre_play');
    }
}
