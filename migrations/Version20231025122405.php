<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231025122405 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE UNIQUE INDEX UNIQ_639F9D7E5E237E06 ON trick_category (name)');
        $this->addSql('ALTER TABLE "user" ADD user_avatar_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE "user" ADD CONSTRAINT FK_8D93D64986D8B6F4 FOREIGN KEY (user_avatar_id) REFERENCES user_picture (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D64986D8B6F4 ON "user" (user_avatar_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE "user" DROP CONSTRAINT FK_8D93D64986D8B6F4');
        $this->addSql('DROP INDEX UNIQ_8D93D64986D8B6F4');
        $this->addSql('ALTER TABLE "user" DROP user_avatar_id');
        $this->addSql('DROP INDEX UNIQ_639F9D7E5E237E06');
    }
}
