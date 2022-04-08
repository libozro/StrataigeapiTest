<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220408061824 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE dossier (id INT AUTO_INCREMENT NOT NULL, raisonsocial VARCHAR(100) NOT NULL, rcccm VARCHAR(100) NOT NULL, datedebut DATE NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ecriture (id INT AUTO_INCREMENT NOT NULL, operation_id INT DEFAULT NULL, libelle VARCHAR(100) NOT NULL, compte VARCHAR(255) NOT NULL, montant DOUBLE PRECISION NOT NULL, date DATE NOT NULL, INDEX IDX_3098DEB44AC3583 (operation_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE exercice (id INT AUTO_INCREMENT NOT NULL, datedebut DATE NOT NULL, datefin DATE NOT NULL, anneecivil DATE NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE operation (id INT AUTO_INCREMENT NOT NULL, exercice_id INT DEFAULT NULL, INDEX IDX_1981A66D89D40298 (exercice_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE ecriture ADD CONSTRAINT FK_3098DEB44AC3583 FOREIGN KEY (operation_id) REFERENCES operation (id)');
        $this->addSql('ALTER TABLE operation ADD CONSTRAINT FK_1981A66D89D40298 FOREIGN KEY (exercice_id) REFERENCES exercice (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE operation DROP FOREIGN KEY FK_1981A66D89D40298');
        $this->addSql('ALTER TABLE ecriture DROP FOREIGN KEY FK_3098DEB44AC3583');
        $this->addSql('DROP TABLE dossier');
        $this->addSql('DROP TABLE ecriture');
        $this->addSql('DROP TABLE exercice');
        $this->addSql('DROP TABLE operation');
    }
}
