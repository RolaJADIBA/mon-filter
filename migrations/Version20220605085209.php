<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220605085209 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE annonces RENAME INDEX titre TO IDX_CB988C6FFF7747B46DE44026');
        $this->addSql('ALTER TABLE user CHANGE photo photo VARCHAR(255) NOT NULL, CHANGE date_naissance date_naissance DATE DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE annonces RENAME INDEX idx_cb988c6fff7747b46de44026 TO titre');
        $this->addSql('ALTER TABLE user CHANGE photo photo VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE date_naissance date_naissance DATETIME DEFAULT NULL');
    }
}
