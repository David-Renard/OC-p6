<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231016091609 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE trick_category ADD trick_id INT NOT NULL');
        $this->addSql('ALTER TABLE trick_video ADD trick_id INT NOT NULL');
        $this->addSql('ALTER TABLE user_picture ADD user_id INT NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE trick_category DROP trick_id');
        $this->addSql('ALTER TABLE trick_video DROP trick_id');
        $this->addSql('ALTER TABLE user_picture DROP user_id');
    }
}
