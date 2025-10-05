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
    }

    public function down(Schema $schema): void
    {
        // TODO : PASSER SUR MARIADB
        //$this->addSql('alter table user drop [column] [if exists] email;');
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('delete from user where id in (1,2);');
    }
}
