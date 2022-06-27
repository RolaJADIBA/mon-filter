<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220627114938 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE annonce_type (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(50) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE annonces (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, annonce_type_id INT DEFAULT NULL, categorie_id INT DEFAULT NULL, titre VARCHAR(255) NOT NULL, images VARCHAR(255) DEFAULT NULL, lieu VARCHAR(50) DEFAULT NULL, created_at DATETIME NOT NULL, description LONGTEXT DEFAULT NULL, prix INT NOT NULL, INDEX IDX_CB988C6FA76ED395 (user_id), INDEX IDX_CB988C6F3F2316F3 (annonce_type_id), INDEX IDX_CB988C6FBCF5E72D (categorie_id), FULLTEXT INDEX IDX_CB988C6FFF7747B46DE44026 (titre, description), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE categories (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(50) NOT NULL, image VARCHAR(255) DEFAULT NULL, color VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE categories_details (id INT AUTO_INCREMENT NOT NULL, categories_id INT DEFAULT NULL, nom VARCHAR(50) NOT NULL, INDEX IDX_5A2CDA2AA21214B7 (categories_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE details (id INT AUTO_INCREMENT NOT NULL, categories_details_id INT DEFAULT NULL, nom VARCHAR(50) NOT NULL, INDEX IDX_72260B8A1A94472A (categories_details_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE petites_details (id INT AUTO_INCREMENT NOT NULL, details_id INT DEFAULT NULL, nom VARCHAR(50) NOT NULL, INDEX IDX_C5105C4FBB1A0722 (details_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reset_password_request (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, selector VARCHAR(20) NOT NULL, hashed_token VARCHAR(100) NOT NULL, requested_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', expires_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_7CE748AA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, is_verified TINYINT(1) NOT NULL, username VARCHAR(50) NOT NULL, date_inscription DATETIME NOT NULL, temps_reponse VARCHAR(20) DEFAULT NULL, telephone VARCHAR(50) DEFAULT NULL, adresse VARCHAR(255) DEFAULT NULL, photo VARCHAR(255) NOT NULL, genre VARCHAR(25) DEFAULT NULL, date_naissance DATE DEFAULT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE annonces ADD CONSTRAINT FK_CB988C6FA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE annonces ADD CONSTRAINT FK_CB988C6F3F2316F3 FOREIGN KEY (annonce_type_id) REFERENCES annonce_type (id)');
        $this->addSql('ALTER TABLE annonces ADD CONSTRAINT FK_CB988C6FBCF5E72D FOREIGN KEY (categorie_id) REFERENCES categories (id)');
        $this->addSql('ALTER TABLE categories_details ADD CONSTRAINT FK_5A2CDA2AA21214B7 FOREIGN KEY (categories_id) REFERENCES categories (id)');
        $this->addSql('ALTER TABLE details ADD CONSTRAINT FK_72260B8A1A94472A FOREIGN KEY (categories_details_id) REFERENCES categories_details (id)');
        $this->addSql('ALTER TABLE petites_details ADD CONSTRAINT FK_C5105C4FBB1A0722 FOREIGN KEY (details_id) REFERENCES details (id)');
        $this->addSql('ALTER TABLE reset_password_request ADD CONSTRAINT FK_7CE748AA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE annonces DROP FOREIGN KEY FK_CB988C6F3F2316F3');
        $this->addSql('ALTER TABLE annonces DROP FOREIGN KEY FK_CB988C6FBCF5E72D');
        $this->addSql('ALTER TABLE categories_details DROP FOREIGN KEY FK_5A2CDA2AA21214B7');
        $this->addSql('ALTER TABLE details DROP FOREIGN KEY FK_72260B8A1A94472A');
        $this->addSql('ALTER TABLE petites_details DROP FOREIGN KEY FK_C5105C4FBB1A0722');
        $this->addSql('ALTER TABLE annonces DROP FOREIGN KEY FK_CB988C6FA76ED395');
        $this->addSql('ALTER TABLE reset_password_request DROP FOREIGN KEY FK_7CE748AA76ED395');
        $this->addSql('DROP TABLE annonce_type');
        $this->addSql('DROP TABLE annonces');
        $this->addSql('DROP TABLE categories');
        $this->addSql('DROP TABLE categories_details');
        $this->addSql('DROP TABLE details');
        $this->addSql('DROP TABLE petites_details');
        $this->addSql('DROP TABLE reset_password_request');
        $this->addSql('DROP TABLE user');
    }
}
