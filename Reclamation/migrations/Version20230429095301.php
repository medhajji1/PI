<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230429095301 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE voiture_rate (id INT AUTO_INCREMENT NOT NULL, voiture VARCHAR(20) NOT NULL, rating INT NOT NULL, note VARCHAR(30) NOT NULL, INDEX IDX_70A645C6E9E2810F (voiture), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE voiture_rate ADD CONSTRAINT FK_70A645C6E9E2810F FOREIGN KEY (voiture) REFERENCES voiture (immatriculation)');
        $this->addSql('DROP TABLE categorie');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE categorie (id INT NOT NULL, marque VARCHAR(20) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, modele VARCHAR(20) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE voiture_rate DROP FOREIGN KEY FK_70A645C6E9E2810F');
        $this->addSql('DROP TABLE voiture_rate');
    }
}
