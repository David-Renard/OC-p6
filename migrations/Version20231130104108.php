<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231130104108 extends AbstractMigration
{


    public function getDescription(): string
    {
        return '';

    }

    public function up(Schema $schema): void
    {
        // This up() migration is auto-generated, please modify it to your needs

    }

    public function down(Schema $schema): void
    {
        // This down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
    }
}
