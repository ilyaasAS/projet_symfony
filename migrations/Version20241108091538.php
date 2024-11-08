<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241108091538 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE item_collection ADD CONSTRAINT FK_41FC4D38FB88E14F FOREIGN KEY (utilisateur_id) REFERENCES uilisateur (id)');
        $this->addSql('CREATE INDEX IDX_41FC4D38FB88E14F ON item_collection (utilisateur_id)');
        $this->addSql('ALTER TABLE post ADD cover VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE item_collection DROP FOREIGN KEY FK_41FC4D38FB88E14F');
        $this->addSql('DROP INDEX IDX_41FC4D38FB88E14F ON item_collection');
        $this->addSql('ALTER TABLE post DROP cover');
    }
}
