<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231024122943 extends AbstractMigration
{


    public function getDescription(): string
    {
        return '';

    }

    public function up(Schema $schema): void
    {
        // This up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE trick ALTER category_id SET NOT NULL');
        $this->addSql('ALTER TABLE trick ALTER author_id SET NOT NULL');
        $this->addSql('ALTER TABLE trick_comment ALTER trick_id SET NOT NULL');
        $this->addSql('ALTER TABLE trick_comment ALTER comment_author_id SET NOT NULL');
        $this->addSql('ALTER TABLE trick_video ALTER trick_id SET NOT NULL');
        $this->addSql('ALTER TABLE "user" ADD username VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // This down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE trick_comment ALTER trick_id DROP NOT NULL');
        $this->addSql('ALTER TABLE trick_comment ALTER comment_author_id DROP NOT NULL');
        $this->addSql('ALTER TABLE trick ALTER category_id DROP NOT NULL');
        $this->addSql('ALTER TABLE trick ALTER author_id DROP NOT NULL');
        $this->addSql('ALTER TABLE "user" DROP username');
        $this->addSql('ALTER TABLE trick_video ALTER trick_id DROP NOT NULL');
    }
}
