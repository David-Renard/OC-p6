<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231024082856 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE trick_picture ADD name VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE trick_picture RENAME COLUMN filename TO url');
        $this->addSql('ALTER TABLE user_picture ADD name VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE user_picture RENAME COLUMN filename TO url');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE user_picture ADD filename VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE user_picture DROP url');
        $this->addSql('ALTER TABLE user_picture DROP name');
        $this->addSql('ALTER TABLE trick_picture ADD filename VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE trick_picture DROP url');
        $this->addSql('ALTER TABLE trick_picture DROP name');
    }
}