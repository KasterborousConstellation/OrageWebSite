<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250910123022 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }
    
    public function up(Schema $schema): void
    {
        $this->down($schema);
        $this->addSql('alter table user add email varchar(180) not null;');
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('INSERT INTO user (id, username, email, roles, password, first_name, last_name) VALUES 
            (1, "Admin", "presidence@asso-orage.fr","[\"ROLE_ADMIN\"]","$2y$13$A.At9O.QrKHBj/jv4r6hmOxE9aVZImJA5YYhBFy.psB9uA7ujnsiS","Admin","System"),
            (2, "User", "contact@asso-orage.fr","[\"ROLE_USER\"]","$2y$13$A.At9O.QrKHBj/jv4r6hmOxE9aVZImJA5YYhBFy.psB9uA7ujnsiS","User","System")
            ;');
    }

    public function down(Schema $schema): void
    {
        // TODO : PASSER SUR MARIADB
        //$this->addSql('alter table user drop [column] [if exists] email;');
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('delete from user where id in (1,2);');
    }
}
