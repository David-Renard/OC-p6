<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231110145916 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE token DROP CONSTRAINT fk_5f37a13b4da1e751');
        $this->addSql('DROP INDEX idx_5f37a13b4da1e751');
        $this->addSql('ALTER TABLE token RENAME COLUMN requested_by_id TO user_info_id');
        $this->addSql('ALTER TABLE token ADD CONSTRAINT FK_5F37A13B586DFF2 FOREIGN KEY (user_info_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_5F37A13B586DFF2 ON token (user_info_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE token DROP CONSTRAINT FK_5F37A13B586DFF2');
        $this->addSql('DROP INDEX IDX_5F37A13B586DFF2');
        $this->addSql('ALTER TABLE token RENAME COLUMN user_info_id TO requested_by_id');
        $this->addSql('ALTER TABLE token ADD CONSTRAINT fk_5f37a13b4da1e751 FOREIGN KEY (requested_by_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_5f37a13b4da1e751 ON token (requested_by_id)');
    }
}
