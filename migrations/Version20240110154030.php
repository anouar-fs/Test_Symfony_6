<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240110154030 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE ecole (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(100) NOT NULL, adresse VARCHAR(200) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE formation (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(200) NOT NULL, volume_horaire INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE formation_ecole (formation_id INT NOT NULL, ecole_id INT NOT NULL, INDEX IDX_7FF3F4D95200282E (formation_id), INDEX IDX_7FF3F4D977EF1B1E (ecole_id), PRIMARY KEY(formation_id, ecole_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE formation_ecole ADD CONSTRAINT FK_7FF3F4D95200282E FOREIGN KEY (formation_id) REFERENCES formation (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE formation_ecole ADD CONSTRAINT FK_7FF3F4D977EF1B1E FOREIGN KEY (ecole_id) REFERENCES ecole (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE formation_ecole DROP FOREIGN KEY FK_7FF3F4D95200282E');
        $this->addSql('ALTER TABLE formation_ecole DROP FOREIGN KEY FK_7FF3F4D977EF1B1E');
        $this->addSql('DROP TABLE ecole');
        $this->addSql('DROP TABLE formation');
        $this->addSql('DROP TABLE formation_ecole');
    }
}
