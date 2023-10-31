<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231031155852 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE "user" DROP CONSTRAINT fk_8d93d64949227b53');
        $this->addSql('DROP INDEX uniq_8d93d64949227b53');
        $this->addSql('ALTER TABLE "user" DROP user_picture_id');
        $this->addSql('ALTER TABLE user_picture DROP CONSTRAINT fk_4ed65183a76ed395');
        $this->addSql('DROP INDEX uniq_4ed65183a76ed395');
        $this->addSql('ALTER TABLE user_picture DROP user_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE user_picture ADD user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user_picture ADD CONSTRAINT fk_4ed65183a76ed395 FOREIGN KEY (user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE UNIQUE INDEX uniq_4ed65183a76ed395 ON user_picture (user_id)');
        $this->addSql('ALTER TABLE "user" ADD user_picture_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE "user" ADD CONSTRAINT fk_8d93d64949227b53 FOREIGN KEY (user_picture_id) REFERENCES user_picture (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE UNIQUE INDEX uniq_8d93d64949227b53 ON "user" (user_picture_id)');
    }
}
