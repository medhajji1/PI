<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230502233851 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE paiement (idpaiement VARCHAR(255) NOT NULL, montant INT NOT NULL, date DATE NOT NULL, motif VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, idContrat INT DEFAULT NULL, INDEX IDX_B1DC7A1E708DF261 (idContrat), PRIMARY KEY(idpaiement)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE paiement ADD CONSTRAINT FK_B1DC7A1E708DF261 FOREIGN KEY (idContrat) REFERENCES contrat (idContrat)');
        $this->addSql('DROP INDEX idReservation ON contrat');
        $this->addSql('ALTER TABLE contrat CHANGE idContrat idContrat INT AUTO_INCREMENT NOT NULL, ADD PRIMARY KEY (idContrat)');
        $this->addSql('ALTER TABLE reclamation CHANGE nom nom VARCHAR(255) NOT NULL, CHANGE email email VARCHAR(255) NOT NULL, CHANGE sujet sujet VARCHAR(255) NOT NULL, CHANGE numtel numtel INT NOT NULL, CHANGE date_submitted date_submitted DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, CHANGE status status VARCHAR(255) DEFAULT NULL, CHANGE severity_level severity_level VARCHAR(255) DEFAULT NULL, CHANGE category category VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE reponse CHANGE message message LONGTEXT NOT NULL');
        $this->addSql('ALTER TABLE reponse ADD CONSTRAINT FK_5FB6DEC7100D1FDF FOREIGN KEY (id_reclamation_id) REFERENCES reclamation (id)');
        $this->addSql('DROP INDEX id_reclamation_id ON reponse');
        $this->addSql('CREATE INDEX IDX_5FB6DEC7100D1FDF ON reponse (id_reclamation_id)');
        $this->addSql('ALTER TABLE utilisateur CHANGE CIN cin INT NOT NULL, CHANGE email email VARCHAR(180) NOT NULL, CHANGE motDePasse motdepasse VARCHAR(255) NOT NULL, CHANGE numeroTelephone numerotelephone VARCHAR(255) NOT NULL, CHANGE typeUtilisateur typeutilisateur VARCHAR(50) NOT NULL, CHANGE image image VARCHAR(200) NOT NULL');
        $this->addSql('DROP INDEX email_index ON utilisateur');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1D1C63B3E7927C74 ON utilisateur (email)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE paiement DROP FOREIGN KEY FK_B1DC7A1E708DF261');
        $this->addSql('DROP TABLE paiement');
        $this->addSql('ALTER TABLE contrat MODIFY idContrat INT NOT NULL');
        $this->addSql('DROP INDEX `primary` ON contrat');
        $this->addSql('ALTER TABLE contrat CHANGE idContrat idContrat INT NOT NULL');
        $this->addSql('CREATE INDEX idReservation ON contrat (idReservation)');
        $this->addSql('ALTER TABLE reclamation CHANGE nom nom VARCHAR(250) NOT NULL, CHANGE email email VARCHAR(250) NOT NULL, CHANGE sujet sujet VARCHAR(250) NOT NULL, CHANGE numtel numtel VARCHAR(250) NOT NULL, CHANGE date_submitted date_submitted DATETIME DEFAULT CURRENT_TIMESTAMP, CHANGE status status VARCHAR(250) NOT NULL, CHANGE severity_level severity_level VARCHAR(250) NOT NULL, CHANGE category category VARCHAR(250) NOT NULL');
        $this->addSql('ALTER TABLE reponse DROP FOREIGN KEY FK_5FB6DEC7100D1FDF');
        $this->addSql('ALTER TABLE reponse DROP FOREIGN KEY FK_5FB6DEC7100D1FDF');
        $this->addSql('ALTER TABLE reponse CHANGE message message TEXT NOT NULL');
        $this->addSql('DROP INDEX idx_5fb6dec7100d1fdf ON reponse');
        $this->addSql('CREATE INDEX id_reclamation_id ON reponse (id_reclamation_id)');
        $this->addSql('ALTER TABLE reponse ADD CONSTRAINT FK_5FB6DEC7100D1FDF FOREIGN KEY (id_reclamation_id) REFERENCES reclamation (id)');
        $this->addSql('ALTER TABLE utilisateur CHANGE cin CIN INT AUTO_INCREMENT NOT NULL, CHANGE email email VARCHAR(30) NOT NULL, CHANGE motdepasse motDePasse VARCHAR(300) DEFAULT NULL, CHANGE numerotelephone numeroTelephone VARCHAR(8) NOT NULL, CHANGE typeutilisateur typeUtilisateur VARCHAR(20) NOT NULL, CHANGE image image VARCHAR(200) DEFAULT \'src/images/default.png\' NOT NULL');
        $this->addSql('DROP INDEX uniq_1d1c63b3e7927c74 ON utilisateur');
        $this->addSql('CREATE UNIQUE INDEX email_Index ON utilisateur (email)');
    }
}
