<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251004141723 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE supported_file_type (id INT AUTO_INCREMENT NOT NULL, ext_name VARCHAR(5) NOT NULL, icon VARCHAR(200) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE depot ADD file_type_id INT NOT NULL');
        $this->addSql('ALTER TABLE depot ADD CONSTRAINT FK_47948BBC9E2A35A8 FOREIGN KEY (file_type_id) REFERENCES supported_file_type (id)');
        $this->addSql('CREATE INDEX IDX_47948BBC9E2A35A8 ON depot (file_type_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE depot DROP FOREIGN KEY FK_47948BBC9E2A35A8');
        $this->addSql('DROP TABLE supported_file_type');
        $this->addSql('DROP INDEX IDX_47948BBC9E2A35A8 ON depot');
        $this->addSql('ALTER TABLE depot DROP file_type_id');
    }
}
