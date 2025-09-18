<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250918171327 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE categorie (id INT AUTO_INCREMENT NOT NULL, libele VARCHAR(100) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE chapitre (id INT AUTO_INCREMENT NOT NULL, cours_id INT NOT NULL, nom_chapitre VARCHAR(100) NOT NULL, INDEX IDX_8C62B0257ECF78B0 (cours_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE chapitre_depot (chapitre_id INT NOT NULL, depot_id INT NOT NULL, INDEX IDX_F30A26DD1FBEEF7B (chapitre_id), INDEX IDX_F30A26DD8510D4DE (depot_id), PRIMARY KEY(chapitre_id, depot_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE cours (id INT AUTO_INCREMENT NOT NULL, categorie_id INT NOT NULL, niveau_id INT NOT NULL, nom_cours VARCHAR(100) NOT NULL, INDEX IDX_FDCA8C9CBCF5E72D (categorie_id), INDEX IDX_FDCA8C9CB3E9C81 (niveau_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE depot (id INT AUTO_INCREMENT NOT NULL, heure_depot DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', path_pdf VARCHAR(1024) NOT NULL, version INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE fiche_exercice (id INT AUTO_INCREMENT NOT NULL, nom_fiche_exercice VARCHAR(255) NOT NULL, description VARCHAR(1000) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE correction_fiche_exercice (fiche_exercice_id INT NOT NULL, depot_id INT NOT NULL, INDEX IDX_74282C6AAA33323 (fiche_exercice_id), INDEX IDX_74282C68510D4DE (depot_id), PRIMARY KEY(fiche_exercice_id, depot_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE fiche_exercice_depot (fiche_exercice_id INT NOT NULL, depot_id INT NOT NULL, INDEX IDX_DF2EBFAAAAA33323 (fiche_exercice_id), INDEX IDX_DF2EBFAA8510D4DE (depot_id), PRIMARY KEY(fiche_exercice_id, depot_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE fiche_exercice_chapitre (fiche_exercice_id INT NOT NULL, chapitre_id INT NOT NULL, INDEX IDX_457C61AAAA33323 (fiche_exercice_id), INDEX IDX_457C61A1FBEEF7B (chapitre_id), PRIMARY KEY(fiche_exercice_id, chapitre_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE niveau (id INT AUTO_INCREMENT NOT NULL, ordre INT DEFAULT NULL, nom_niveau VARCHAR(50) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE qcm (id INT AUTO_INCREMENT NOT NULL, nom_qcm VARCHAR(100) NOT NULL, note_max DOUBLE PRECISION NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE question (id INT AUTO_INCREMENT NOT NULL, format_response VARCHAR(5) NOT NULL, intitule VARCHAR(2500) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE wrong_answer (question_id INT NOT NULL, reponse_id INT NOT NULL, INDEX IDX_5DA89B971E27F6BF (question_id), INDEX IDX_5DA89B97CF18BB82 (reponse_id), PRIMARY KEY(question_id, reponse_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE correct_answer (question_id INT NOT NULL, reponse_id INT NOT NULL, INDEX IDX_A203B7E01E27F6BF (question_id), INDEX IDX_A203B7E0CF18BB82 (reponse_id), PRIMARY KEY(question_id, reponse_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reponse (id INT AUTO_INCREMENT NOT NULL, libele VARCHAR(1000) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tentative_qcm (id INT AUTO_INCREMENT NOT NULL, rel_qcm_id INT NOT NULL, note DOUBLE PRECISION NOT NULL, at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_72C3FE1C1BBB3597 (rel_qcm_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tentative_qcm_user (tentative_qcm_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_9DFF8E28FB2FC06F (tentative_qcm_id), INDEX IDX_9DFF8E28A76ED395 (user_id), PRIMARY KEY(tentative_qcm_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE chapitre ADD CONSTRAINT FK_8C62B0257ECF78B0 FOREIGN KEY (cours_id) REFERENCES cours (id)');
        $this->addSql('ALTER TABLE chapitre_depot ADD CONSTRAINT FK_F30A26DD1FBEEF7B FOREIGN KEY (chapitre_id) REFERENCES chapitre (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE chapitre_depot ADD CONSTRAINT FK_F30A26DD8510D4DE FOREIGN KEY (depot_id) REFERENCES depot (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE cours ADD CONSTRAINT FK_FDCA8C9CBCF5E72D FOREIGN KEY (categorie_id) REFERENCES categorie (id)');
        $this->addSql('ALTER TABLE cours ADD CONSTRAINT FK_FDCA8C9CB3E9C81 FOREIGN KEY (niveau_id) REFERENCES niveau (id)');
        $this->addSql('ALTER TABLE correction_fiche_exercice ADD CONSTRAINT FK_74282C6AAA33323 FOREIGN KEY (fiche_exercice_id) REFERENCES fiche_exercice (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE correction_fiche_exercice ADD CONSTRAINT FK_74282C68510D4DE FOREIGN KEY (depot_id) REFERENCES depot (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE fiche_exercice_depot ADD CONSTRAINT FK_DF2EBFAAAAA33323 FOREIGN KEY (fiche_exercice_id) REFERENCES fiche_exercice (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE fiche_exercice_depot ADD CONSTRAINT FK_DF2EBFAA8510D4DE FOREIGN KEY (depot_id) REFERENCES depot (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE fiche_exercice_chapitre ADD CONSTRAINT FK_457C61AAAA33323 FOREIGN KEY (fiche_exercice_id) REFERENCES fiche_exercice (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE fiche_exercice_chapitre ADD CONSTRAINT FK_457C61A1FBEEF7B FOREIGN KEY (chapitre_id) REFERENCES chapitre (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE wrong_answer ADD CONSTRAINT FK_5DA89B971E27F6BF FOREIGN KEY (question_id) REFERENCES question (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE wrong_answer ADD CONSTRAINT FK_5DA89B97CF18BB82 FOREIGN KEY (reponse_id) REFERENCES reponse (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE correct_answer ADD CONSTRAINT FK_A203B7E01E27F6BF FOREIGN KEY (question_id) REFERENCES question (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE correct_answer ADD CONSTRAINT FK_A203B7E0CF18BB82 FOREIGN KEY (reponse_id) REFERENCES reponse (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE tentative_qcm ADD CONSTRAINT FK_72C3FE1C1BBB3597 FOREIGN KEY (rel_qcm_id) REFERENCES qcm (id)');
        $this->addSql('ALTER TABLE tentative_qcm_user ADD CONSTRAINT FK_9DFF8E28FB2FC06F FOREIGN KEY (tentative_qcm_id) REFERENCES tentative_qcm (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE tentative_qcm_user ADD CONSTRAINT FK_9DFF8E28A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user CHANGE last_validatin_email last_validatin_email DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE chapitre DROP FOREIGN KEY FK_8C62B0257ECF78B0');
        $this->addSql('ALTER TABLE chapitre_depot DROP FOREIGN KEY FK_F30A26DD1FBEEF7B');
        $this->addSql('ALTER TABLE chapitre_depot DROP FOREIGN KEY FK_F30A26DD8510D4DE');
        $this->addSql('ALTER TABLE cours DROP FOREIGN KEY FK_FDCA8C9CBCF5E72D');
        $this->addSql('ALTER TABLE cours DROP FOREIGN KEY FK_FDCA8C9CB3E9C81');
        $this->addSql('ALTER TABLE correction_fiche_exercice DROP FOREIGN KEY FK_74282C6AAA33323');
        $this->addSql('ALTER TABLE correction_fiche_exercice DROP FOREIGN KEY FK_74282C68510D4DE');
        $this->addSql('ALTER TABLE fiche_exercice_depot DROP FOREIGN KEY FK_DF2EBFAAAAA33323');
        $this->addSql('ALTER TABLE fiche_exercice_depot DROP FOREIGN KEY FK_DF2EBFAA8510D4DE');
        $this->addSql('ALTER TABLE fiche_exercice_chapitre DROP FOREIGN KEY FK_457C61AAAA33323');
        $this->addSql('ALTER TABLE fiche_exercice_chapitre DROP FOREIGN KEY FK_457C61A1FBEEF7B');
        $this->addSql('ALTER TABLE wrong_answer DROP FOREIGN KEY FK_5DA89B971E27F6BF');
        $this->addSql('ALTER TABLE wrong_answer DROP FOREIGN KEY FK_5DA89B97CF18BB82');
        $this->addSql('ALTER TABLE correct_answer DROP FOREIGN KEY FK_A203B7E01E27F6BF');
        $this->addSql('ALTER TABLE correct_answer DROP FOREIGN KEY FK_A203B7E0CF18BB82');
        $this->addSql('ALTER TABLE tentative_qcm DROP FOREIGN KEY FK_72C3FE1C1BBB3597');
        $this->addSql('ALTER TABLE tentative_qcm_user DROP FOREIGN KEY FK_9DFF8E28FB2FC06F');
        $this->addSql('ALTER TABLE tentative_qcm_user DROP FOREIGN KEY FK_9DFF8E28A76ED395');
        $this->addSql('DROP TABLE categorie');
        $this->addSql('DROP TABLE chapitre');
        $this->addSql('DROP TABLE chapitre_depot');
        $this->addSql('DROP TABLE cours');
        $this->addSql('DROP TABLE depot');
        $this->addSql('DROP TABLE fiche_exercice');
        $this->addSql('DROP TABLE correction_fiche_exercice');
        $this->addSql('DROP TABLE fiche_exercice_depot');
        $this->addSql('DROP TABLE fiche_exercice_chapitre');
        $this->addSql('DROP TABLE niveau');
        $this->addSql('DROP TABLE qcm');
        $this->addSql('DROP TABLE question');
        $this->addSql('DROP TABLE wrong_answer');
        $this->addSql('DROP TABLE correct_answer');
        $this->addSql('DROP TABLE reponse');
        $this->addSql('DROP TABLE tentative_qcm');
        $this->addSql('DROP TABLE tentative_qcm_user');
        $this->addSql('ALTER TABLE user CHANGE last_validatin_email last_validatin_email DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
    }
}
