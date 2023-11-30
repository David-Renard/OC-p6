<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231031155104 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE trick_comment DROP CONSTRAINT fk_f7292b341f0b124d');
        $this->addSql('DROP INDEX idx_f7292b341f0b124d');
        $this->addSql('ALTER TABLE trick_comment RENAME COLUMN comment_author_id TO user_id');
        $this->addSql('ALTER TABLE trick_comment ADD CONSTRAINT FK_F7292B34A76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_F7292B34A76ED395 ON trick_comment (user_id)');
        $this->addSql('ALTER TABLE "user" DROP CONSTRAINT fk_8d93d64986383b10');
        $this->addSql('DROP INDEX uniq_8d93d64986383b10');
        $this->addSql('ALTER TABLE "user" RENAME COLUMN avatar_id TO user_picture_id');
        $this->addSql('ALTER TABLE "user" ADD CONSTRAINT FK_8D93D64949227B53 FOREIGN KEY (user_picture_id) REFERENCES user_picture (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D64949227B53 ON "user" (user_picture_id)');
        $this->addSql('ALTER TABLE user_picture DROP CONSTRAINT fk_4ed65183586dff2');
        $this->addSql('DROP INDEX uniq_4ed65183586dff2');
        $this->addSql('ALTER TABLE user_picture RENAME COLUMN user_info_id TO user_id');
        $this->addSql('ALTER TABLE user_picture ADD CONSTRAINT FK_4ED65183A76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_4ED65183A76ED395 ON user_picture (user_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE trick_comment DROP CONSTRAINT FK_F7292B34A76ED395');
        $this->addSql('DROP INDEX IDX_F7292B34A76ED395');
        $this->addSql('ALTER TABLE trick_comment RENAME COLUMN user_id TO comment_author_id');
        $this->addSql('ALTER TABLE trick_comment ADD CONSTRAINT fk_f7292b341f0b124d FOREIGN KEY (comment_author_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_f7292b341f0b124d ON trick_comment (comment_author_id)');
        $this->addSql('ALTER TABLE user_picture DROP CONSTRAINT FK_4ED65183A76ED395');
        $this->addSql('DROP INDEX UNIQ_4ED65183A76ED395');
        $this->addSql('ALTER TABLE user_picture RENAME COLUMN user_id TO user_info_id');
        $this->addSql('ALTER TABLE user_picture ADD CONSTRAINT fk_4ed65183586dff2 FOREIGN KEY (user_info_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE UNIQUE INDEX uniq_4ed65183586dff2 ON user_picture (user_info_id)');
        $this->addSql('ALTER TABLE "user" DROP CONSTRAINT FK_8D93D64949227B53');
        $this->addSql('DROP INDEX UNIQ_8D93D64949227B53');
        $this->addSql('ALTER TABLE "user" RENAME COLUMN user_picture_id TO avatar_id');
        $this->addSql('ALTER TABLE "user" ADD CONSTRAINT fk_8d93d64986383b10 FOREIGN KEY (avatar_id) REFERENCES user_picture (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE UNIQUE INDEX uniq_8d93d64986383b10 ON "user" (avatar_id)');
    }
}