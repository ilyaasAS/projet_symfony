<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241107133934 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add nullable utilisateur_id to item_collection and set up foreign key constraint.';
    }

    public function up(Schema $schema): void
    {
        // Ajouter la colonne utilisateur_id à la table item_collection comme nullable
        $this->addSql('ALTER TABLE item_collection ADD utilisateur_id INT DEFAULT NULL');

        // Ajouter la contrainte de clé étrangère avec le bon nom de table (uilisateur)
        $this->addSql('ALTER TABLE item_collection ADD CONSTRAINT FK_41FC4D38FB88E14F FOREIGN KEY (utilisateur_id) REFERENCES uilisateur (id)');

        // Créer l'index pour améliorer les performances des jointures
        $this->addSql('CREATE INDEX IDX_41FC4D38FB88E14F ON item_collection (utilisateur_id)');
    }

    public function down(Schema $schema): void
    {
        // Supprimer la clé étrangère
        $this->addSql('ALTER TABLE item_collection DROP FOREIGN KEY FK_41FC4D38FB88E14F');

        // Supprimer l'index
        $this->addSql('DROP INDEX IDX_41FC4D38FB88E14F ON item_collection');

        // Supprimer la colonne utilisateur_id
        $this->addSql('ALTER TABLE item_collection DROP utilisateur_id');
    }
}
