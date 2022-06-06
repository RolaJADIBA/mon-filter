<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220605075847 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE annonces RENAME INDEX titre TO IDX_CB988C6FFF7747B46DE44026');
        $this->addSql('ALTER TABLE user ADD genre VARCHAR(25) DEFAULT NULL, ADD date_naissance DATETIME DEFAULT NULL, CHANGE photo photo VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE annonces RENAME INDEX idx_cb988c6fff7747b46de44026 TO titre');
        $this->addSql('ALTER TABLE user DROP genre, DROP date_naissance, CHANGE photo photo VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`');
    }
}
