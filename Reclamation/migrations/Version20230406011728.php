<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230406011728 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE reclamation (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, sujet VARCHAR(255) NOT NULL, message VARCHAR(255) NOT NULL, numtel INT NOT NULL, date_submitted DATE DEFAULT NULL, status VARCHAR(255) DEFAULT NULL, severity_level VARCHAR(255) DEFAULT NULL, category VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY reservation_ibfk_1');
        $this->addSql('ALTER TABLE voiture DROP FOREIGN KEY voiture_ibfk_1');
        $this->addSql('DROP TABLE categorie');
        $this->addSql('DROP TABLE reservation');
        $this->addSql('DROP TABLE voiture');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE categorie (id INT AUTO_INCREMENT NOT NULL, marque VARCHAR(20) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, modele VARCHAR(20) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE reservation (id INT AUTO_INCREMENT NOT NULL, voiture VARCHAR(20) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, date_d DATE NOT NULL, date_f DATE NOT NULL, INDEX voiture (voiture), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE voiture (immatriculation VARCHAR(250) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, categorie INT NOT NULL, kilometrage INT NOT NULL, couleur VARCHAR(250) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, image VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, INDEX categorie (categorie), PRIMARY KEY(immatriculation)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT reservation_ibfk_1 FOREIGN KEY (voiture) REFERENCES voiture (immatriculation)');
        $this->addSql('ALTER TABLE voiture ADD CONSTRAINT voiture_ibfk_1 FOREIGN KEY (categorie) REFERENCES categorie (id)');
        $this->addSql('DROP TABLE reclamation');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
