<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240402220811 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE orderproduct DROP FOREIGN KEY orderproduct_ibfk_1');
        $this->addSql('ALTER TABLE participation DROP FOREIGN KEY participation_ibfk_1');
        $this->addSql('ALTER TABLE participation DROP FOREIGN KEY participation_ibfk_2');
        $this->addSql('DROP TABLE event');
        $this->addSql('DROP TABLE orderproduct');
        $this->addSql('DROP TABLE participation');
        $this->addSql('DROP TABLE product');
        $this->addSql('DROP TABLE workshop');
        $this->addSql('ALTER TABLE auction CHANGE nom nom VARCHAR(150) NOT NULL, CHANGE date_cloture date_cloture DATETIME NOT NULL, CHANGE date_lancement date_lancement DATETIME NOT NULL, CHANGE prix_initial prix_initial DOUBLE PRECISION NOT NULL');
        $this->addSql('DROP INDEX Id_Participant ON auction_participant');
        $this->addSql('ALTER TABLE auction_participant ADD ratingove INT NOT NULL, CHANGE Id_Participant id_participant INT NOT NULL, CHANGE Id_Auction id_auction INT DEFAULT NULL, CHANGE prix prix DOUBLE PRECISION NOT NULL, CHANGE date date DATE NOT NULL, CHANGE Love love INT NOT NULL');
        $this->addSql('ALTER TABLE auction_participant ADD CONSTRAINT FK_883B758D795CE3 FOREIGN KEY (id_auction) REFERENCES auction (idAuction)');
        $this->addSql('DROP INDEX id_auction ON auction_participant');
        $this->addSql('CREATE INDEX IDX_883B758D795CE3 ON auction_participant (id_auction)');
        $this->addSql('DROP INDEX idReciever ON discussion');
        $this->addSql('DROP INDEX idSender ON discussion');
        $this->addSql('ALTER TABLE discussion CHANGE Sig sig VARCHAR(255) NOT NULL');
        $this->addSql('DROP INDEX idsender ON message');
        $this->addSql('DROP INDEX iddis ON message');
        $this->addSql('ALTER TABLE message CHANGE reaction reaction VARCHAR(255) NOT NULL, CHANGE vu vu INT NOT NULL, CHANGE datasent datasent DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE post CHANGE id_post id_post VARCHAR(255) NOT NULL, CHANGE id_forum id_forum INT DEFAULT NULL, CHANGE id_user id_user INT DEFAULT NULL, CHANGE TimeofCreation TimeofCreation DATETIME NOT NULL');
        $this->addSql('ALTER TABLE post ADD CONSTRAINT FK_5A8A6C8D6BAEFFFD FOREIGN KEY (id_forum) REFERENCES forum (id_forum)');
        $this->addSql('ALTER TABLE post ADD CONSTRAINT FK_5A8A6C8D6B3CA4B FOREIGN KEY (id_user) REFERENCES user (Id_User)');
        $this->addSql('DROP INDEX id_forum ON post');
        $this->addSql('CREATE INDEX IDX_5A8A6C8D6BAEFFFD ON post (id_forum)');
        $this->addSql('DROP INDEX id_user ON post');
        $this->addSql('CREATE INDEX IDX_5A8A6C8D6B3CA4B ON post (id_user)');
        $this->addSql('ALTER TABLE user CHANGE Username username VARCHAR(255) NOT NULL, CHANGE Email email VARCHAR(255) NOT NULL, CHANGE Password password VARCHAR(255) NOT NULL, CHANGE Role role VARCHAR(255) NOT NULL, CHANGE FirstName firstname VARCHAR(255) NOT NULL, CHANGE Lastname lastname VARCHAR(255) NOT NULL, CHANGE Adress adress VARCHAR(255) NOT NULL, CHANGE Phone phone VARCHAR(255) NOT NULL, CHANGE Gender gender VARCHAR(255) NOT NULL, CHANGE DOB dob VARCHAR(255) NOT NULL, CHANGE ImageURL imageurl VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE event (Id_Event INT AUTO_INCREMENT NOT NULL, E_Name VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, Place VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, E_Date DATE NOT NULL, Ticket_Price DOUBLE PRECISION NOT NULL, image VARCHAR(500) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, PRIMARY KEY(Id_Event)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE orderproduct (Id_Order INT AUTO_INCREMENT NOT NULL, Id_Product INT NOT NULL, Price DOUBLE PRECISION NOT NULL, Title VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, OrderDate VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, Prod_img VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, INDEX Id_Product_2 (Id_Product), INDEX Id_Product (Id_Product), PRIMARY KEY(Id_Order)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE participation (Id_Participation INT NOT NULL, Id_Event INT DEFAULT NULL, Id_User INT DEFAULT NULL, INDEX Id_User (Id_User), INDEX Id_Event (Id_Event), PRIMARY KEY(Id_Participation)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE product (Id_Product INT AUTO_INCREMENT NOT NULL, Id_User INT NOT NULL, Title VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, Description VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, ForSale TINYINT(1) NOT NULL, Price DOUBLE PRECISION NOT NULL, CreationDate VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, ProductImage VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, INDEX Id_User (Id_User), PRIMARY KEY(Id_Product)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE workshop (Id_Workshop INT AUTO_INCREMENT NOT NULL, Title VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, Details VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, image VARCHAR(500) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, Id_Event INT NOT NULL, INDEX Id_Event (Id_Event), PRIMARY KEY(Id_Workshop)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE orderproduct ADD CONSTRAINT orderproduct_ibfk_1 FOREIGN KEY (Id_Product) REFERENCES product (Id_Product)');
        $this->addSql('ALTER TABLE participation ADD CONSTRAINT participation_ibfk_1 FOREIGN KEY (Id_Event) REFERENCES event (Id_Event)');
        $this->addSql('ALTER TABLE participation ADD CONSTRAINT participation_ibfk_2 FOREIGN KEY (Id_User) REFERENCES user (Id_User)');
        $this->addSql('DROP TABLE messenger_messages');
        $this->addSql('ALTER TABLE auction CHANGE nom nom VARCHAR(255) NOT NULL, CHANGE date_cloture date_cloture DATE DEFAULT NULL, CHANGE date_lancement date_lancement DATE DEFAULT NULL, CHANGE prix_initial prix_initial DOUBLE PRECISION DEFAULT NULL');
        $this->addSql('ALTER TABLE auction_participant DROP FOREIGN KEY FK_883B758D795CE3');
        $this->addSql('ALTER TABLE auction_participant DROP FOREIGN KEY FK_883B758D795CE3');
        $this->addSql('ALTER TABLE auction_participant DROP ratingove, CHANGE id_participant Id_Participant INT AUTO_INCREMENT NOT NULL, CHANGE id_auction Id_Auction INT NOT NULL, CHANGE prix prix DOUBLE PRECISION DEFAULT NULL, CHANGE date date DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, CHANGE love Love INT DEFAULT 0 NOT NULL');
        $this->addSql('CREATE INDEX Id_Participant ON auction_participant (Id_Participant)');
        $this->addSql('DROP INDEX idx_883b758d795ce3 ON auction_participant');
        $this->addSql('CREATE INDEX Id_Auction ON auction_participant (Id_Auction)');
        $this->addSql('ALTER TABLE auction_participant ADD CONSTRAINT FK_883B758D795CE3 FOREIGN KEY (id_auction) REFERENCES auction (idAuction)');
        $this->addSql('ALTER TABLE discussion CHANGE sig Sig VARCHAR(255) DEFAULT NULL');
        $this->addSql('CREATE INDEX idReciever ON discussion (idReciever)');
        $this->addSql('CREATE INDEX idSender ON discussion (idSender)');
        $this->addSql('ALTER TABLE message CHANGE reaction reaction VARCHAR(255) DEFAULT NULL, CHANGE vu vu INT DEFAULT NULL, CHANGE datasent datasent DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL');
        $this->addSql('CREATE INDEX idsender ON message (idsender)');
        $this->addSql('CREATE INDEX iddis ON message (iddis)');
        $this->addSql('ALTER TABLE post DROP FOREIGN KEY FK_5A8A6C8D6BAEFFFD');
        $this->addSql('ALTER TABLE post DROP FOREIGN KEY FK_5A8A6C8D6B3CA4B');
        $this->addSql('ALTER TABLE post DROP FOREIGN KEY FK_5A8A6C8D6BAEFFFD');
        $this->addSql('ALTER TABLE post DROP FOREIGN KEY FK_5A8A6C8D6B3CA4B');
        $this->addSql('ALTER TABLE post CHANGE id_post id_post INT AUTO_INCREMENT NOT NULL, CHANGE id_forum id_forum INT NOT NULL, CHANGE id_user id_user INT NOT NULL, CHANGE TimeofCreation TimeofCreation DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL');
        $this->addSql('DROP INDEX idx_5a8a6c8d6b3ca4b ON post');
        $this->addSql('CREATE INDEX id_user ON post (id_user)');
        $this->addSql('DROP INDEX idx_5a8a6c8d6baefffd ON post');
        $this->addSql('CREATE INDEX id_forum ON post (id_forum)');
        $this->addSql('ALTER TABLE post ADD CONSTRAINT FK_5A8A6C8D6BAEFFFD FOREIGN KEY (id_forum) REFERENCES forum (id_forum)');
        $this->addSql('ALTER TABLE post ADD CONSTRAINT FK_5A8A6C8D6B3CA4B FOREIGN KEY (id_user) REFERENCES user (Id_User)');
        $this->addSql('ALTER TABLE user CHANGE username Username VARCHAR(255) DEFAULT NULL, CHANGE email Email VARCHAR(255) DEFAULT NULL, CHANGE password Password VARCHAR(255) DEFAULT NULL, CHANGE role Role VARCHAR(255) DEFAULT NULL, CHANGE firstname FirstName VARCHAR(255) DEFAULT NULL, CHANGE lastname Lastname VARCHAR(255) DEFAULT NULL, CHANGE adress Adress VARCHAR(255) DEFAULT NULL, CHANGE phone Phone VARCHAR(255) DEFAULT NULL, CHANGE gender Gender VARCHAR(255) DEFAULT NULL, CHANGE dob DOB DATE DEFAULT NULL, CHANGE imageurl ImageURL VARCHAR(255) DEFAULT NULL');
    }
}
