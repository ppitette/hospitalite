<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221209182614 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX UNIQ_5E90F6D6C4F49286 ON inscription');
        $this->addSql('ALTER TABLE inscription CHANGE inscrit_at inscrit_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', CHANGE envoi_at envoi_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', CHANGE retour_at retour_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', CHANGE valide_at valide_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', CHANGE desiste_at desiste_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE participation CHANGE voyage voyage VARCHAR(2) NOT NULL');
        $this->addSql('ALTER TABLE pelerinage CHANGE abrege abrege VARCHAR(20) NOT NULL, CHANGE debut debut DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', CHANGE fin fin DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE personne CHANGE date_naiss date_naiss DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', CHANGE date_deces date_deces DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', CHANGE deleted_at deleted_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE inscription CHANGE inscrit_at inscrit_at DATETIME DEFAULT NULL, CHANGE envoi_at envoi_at DATETIME DEFAULT NULL, CHANGE retour_at retour_at DATETIME DEFAULT NULL, CHANGE valide_at valide_at DATETIME DEFAULT NULL, CHANGE desiste_at desiste_at DATETIME DEFAULT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_5E90F6D6C4F49286 ON inscription (num_insc)');
        $this->addSql('ALTER TABLE personne CHANGE date_naiss date_naiss DATETIME DEFAULT NULL, CHANGE date_deces date_deces DATETIME DEFAULT NULL, CHANGE deleted_at deleted_at DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE pelerinage CHANGE abrege abrege VARCHAR(15) NOT NULL, CHANGE debut debut DATETIME NOT NULL, CHANGE fin fin DATETIME NOT NULL');
        $this->addSql('ALTER TABLE participation CHANGE voyage voyage VARCHAR(1) NOT NULL');
    }
}
