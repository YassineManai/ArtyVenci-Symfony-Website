<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231024150502 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE condidat ADD entretien_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE condidat ADD CONSTRAINT FK_3A8ACF2C548DCEA2 FOREIGN KEY (entretien_id) REFERENCES entretien (id)');
        $this->addSql('CREATE INDEX IDX_3A8ACF2C548DCEA2 ON condidat (entretien_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE condidat DROP FOREIGN KEY FK_3A8ACF2C548DCEA2');
        $this->addSql('DROP INDEX IDX_3A8ACF2C548DCEA2 ON condidat');
        $this->addSql('ALTER TABLE condidat DROP entretien_id');
    }
}
