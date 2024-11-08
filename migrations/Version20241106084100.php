<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241106084100 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // Modifie la colonne 'catégorie' pour 'categorie' et change sa définition
        $this->addSql('ALTER TABLE item_collection CHANGE catégorie categorie VARCHAR(50) DEFAULT NULL');
        
        // Modification de la colonne 'is_public' pour qu'elle ne soit jamais NULL, avec une valeur par défaut de 0
        $this->addSql('ALTER TABLE item_collection CHANGE is_public is_public TINYINT(1) NOT NULL DEFAULT 0');
    }

    public function down(Schema $schema): void
    {
        // Annule la modification de la colonne 'categorie' pour revenir à 'catégorie'
        $this->addSql('ALTER TABLE item_collection CHANGE categorie catégorie VARCHAR(50) DEFAULT NULL');
        
        // Annule la modification de la colonne 'is_public', en supprimant la valeur par défaut
        $this->addSql('ALTER TABLE item_collection CHANGE is_public is_public TINYINT(1) DEFAULT NULL');
    }
}
