<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250803175807 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('INSERT into faq_question (id,question_name,description) values
        (1,"Comment s\'inscrire ?","Pour vous inscrire, cliquez sur le bouton d\'inscription en haut à droite de la page d\'accueil et suivez les instructions."),
        (2,"Comment réinitialiser mon mot de passe ?","Si vous avez oublié votre mot de passe, cliquez sur le lien de réinitialisation du mot de passe sur la page de connexion."),
        (3,"Comment contacter le support ?","Vous pouvez contacter le support via le formulaire de contact disponible dans la section Contact puis \'Par mail\'."),
        (4,"Comment modifier mes informations personnelles ?","Pour modifier vos informations personnelles, allez dans votre profil et cliquez sur \'Modifier\'."),
        (5,"Comment supprimer mon compte ?","Supprimer son compte permet de détruite toute les données associées à votre compte comme vous le garanti le RGPD. Pour supprimer votre compte, allez dans les paramètres de votre compte et cliquez sur \'Supprimer mon compte\'. Des informations peuvent être gardés pour des raisons légales, mais elles ne seront pas associées à votre compte.")
        ');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DELETE FROM faq_question WHERE id IN (1,2,3,4,5)');
    }
}