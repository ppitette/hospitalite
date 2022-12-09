<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221209172316 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE adresse (id INT AUTO_INCREMENT NOT NULL, remise VARCHAR(50) DEFAULT NULL, comp_loc VARCHAR(50) DEFAULT NULL, num_voie VARCHAR(10) DEFAULT NULL, type_voie VARCHAR(20) DEFAULT NULL, nom_voie VARCHAR(50) DEFAULT NULL, comp_voie VARCHAR(50) DEFAULT NULL, insee VARCHAR(5) DEFAULT NULL, c_postal VARCHAR(10) DEFAULT NULL, commune VARCHAR(50) DEFAULT NULL, pays VARCHAR(70) DEFAULT NULL, paroisse VARCHAR(50) DEFAULT NULL, secteur VARCHAR(50) DEFAULT NULL, diocese VARCHAR(50) DEFAULT NULL, lat NUMERIC(10, 8) DEFAULT NULL, lng NUMERIC(10, 8) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE inscription (id INT AUTO_INCREMENT NOT NULL, num_insc INT NOT NULL, inscrit_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', entite INT NOT NULL, couple TINYINT(1) NOT NULL, conjoint VARCHAR(50) DEFAULT NULL, min_dest VARCHAR(50) DEFAULT NULL, nouveau TINYINT(1) NOT NULL, hors_effectif TINYINT(1) NOT NULL, liste_att TINYINT(1) NOT NULL, voy_aller TINYINT(1) NOT NULL, voy_retour TINYINT(1) NOT NULL, situation INT NOT NULL, connu_hosp INT NOT NULL, connu_hosp_qui VARCHAR(50) DEFAULT NULL, heb_hosp TINYINT(1) NOT NULL, pref_heberg INT NOT NULL, heb_single TINYINT(1) NOT NULL, heb_perso VARCHAR(50) DEFAULT NULL, partage_chambre INT NOT NULL, partage_chambre_nom VARCHAR(50) DEFAULT NULL, envoi_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', retour_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', valide_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', desiste_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', personne_urgence VARCHAR(255) DEFAULT NULL, service_chambre INT NOT NULL, porte TINYINT(1) NOT NULL, voiture TINYINT(1) NOT NULL, garde_nuit TINYINT(1) NOT NULL, piscine TINYINT(1) NOT NULL, animation TINYINT(1) NOT NULL, instrument VARCHAR(50) DEFAULT NULL, tenue TINYINT(1) NOT NULL, heb_hotel INT NOT NULL, heb_chambre VARCHAR(5) DEFAULT NULL, trns_resp INT NOT NULL, trns_car INT NOT NULL, trns_place VARCHAR(4) DEFAULT NULL, trns_siege INT NOT NULL, srvs_depart LONGTEXT DEFAULT NULL, srvs_embarq VARCHAR(255) DEFAULT NULL, srvs_voyage VARCHAR(255) DEFAULT NULL, pele_resp INT NOT NULL, groupe INT NOT NULL, service VARCHAR(30) DEFAULT NULL, srvs_chambre VARCHAR(10) DEFAULT NULL, srvs_linge TINYINT(1) NOT NULL, perm_accueil TINYINT(1) NOT NULL, perm_accueil_lib VARCHAR(25) DEFAULT NULL, perm_nuit TINYINT(1) NOT NULL, perm_nuit_lib VARCHAR(25) DEFAULT NULL, srvs_eau TINYINT(1) NOT NULL, srvs_ascenseur TINYINT(1) NOT NULL, srvs_hygiene TINYINT(1) NOT NULL, srvs_piscines TINYINT(1) NOT NULL, srvs_menage TINYINT(1) NOT NULL, srvs_medicament TINYINT(1) NOT NULL, srvs_covid VARCHAR(50) DEFAULT NULL, current_place VARCHAR(20) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE participation (id INT AUTO_INCREMENT NOT NULL, age_pele INT NOT NULL, num_insc INT NOT NULL, entite VARCHAR(2) NOT NULL, hors_effectif TINYINT(1) NOT NULL, desist TINYINT(1) NOT NULL, resp VARCHAR(100) DEFAULT NULL, medical VARCHAR(100) DEFAULT NULL, voyage VARCHAR(2) NOT NULL, groupe VARCHAR(20) DEFAULT NULL, hebergement VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE pelerinage (id INT AUTO_INCREMENT NOT NULL, cle VARCHAR(5) NOT NULL, libelle VARCHAR(50) NOT NULL, abrege VARCHAR(20) NOT NULL, debut DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', fin DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', theme VARCHAR(255) DEFAULT NULL, remarque LONGTEXT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE personne (id INT AUTO_INCREMENT NOT NULL, liste VARCHAR(5) DEFAULT NULL, eglise VARCHAR(6) DEFAULT NULL, civilite VARCHAR(10) DEFAULT NULL, nom VARCHAR(50) DEFAULT NULL, nom_naiss VARCHAR(50) DEFAULT NULL, prenom VARCHAR(50) DEFAULT NULL, genre INT NOT NULL, telephone VARCHAR(20) DEFAULT NULL, mobile VARCHAR(10) DEFAULT NULL, lr_courriel TINYINT(1) NOT NULL, courriel VARCHAR(255) DEFAULT NULL, courriel_remarque VARCHAR(255) DEFAULT NULL, date_naiss DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', decede TINYINT(1) NOT NULL, date_deces DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', eng_hosp INT NOT NULL, eng_egl INT NOT NULL, p_pele INT NOT NULL, nb_pele INT NOT NULL, d_pele INT NOT NULL, medical INT NOT NULL, medical_autre VARCHAR(50) DEFAULT NULL, is_referent TINYINT(1) NOT NULL, adr_ident INT DEFAULT NULL, deleted_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, first_name VARCHAR(50) NOT NULL, last_name VARCHAR(50) NOT NULL, is_enable TINYINT(1) NOT NULL, registered_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', must_be_verified_before DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', registration_token VARCHAR(255) DEFAULT NULL, is_verified TINYINT(1) NOT NULL, verified_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', frgt_pswd_token VARCHAR(255) DEFAULT NULL, frgt_pswd_token_requested_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', frgt_pswd_token_must_be_verified_before DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', frgt_pswd_token_verified_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE adresse');
        $this->addSql('DROP TABLE inscription');
        $this->addSql('DROP TABLE participation');
        $this->addSql('DROP TABLE pelerinage');
        $this->addSql('DROP TABLE personne');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
