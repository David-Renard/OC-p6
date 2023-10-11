<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231011134805 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE trick_category_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE trick_comment_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE trick_picture_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE trick_video_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE user_info_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE user_picture_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE trick_category (id INT NOT NULL, name VARCHAR(20) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE trick_comment (id INT NOT NULL, content TEXT NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, author INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN trick_comment.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE trick_picture (id INT NOT NULL, filename VARCHAR(255) NOT NULL, main BOOLEAN NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE trick_video (id INT NOT NULL, url VARCHAR(2087) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE user_info (id INT NOT NULL, name VARCHAR(255) NOT NULL, firstname VARCHAR(255) NOT NULL, login VARCHAR(255) NOT NULL, email TEXT NOT NULL, password VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE user_picture (id INT NOT NULL, filename VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_D8F0A91E5E237E06 ON trick (name)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE trick_category_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE trick_comment_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE trick_picture_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE trick_video_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE user_info_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE user_picture_id_seq CASCADE');
        $this->addSql('DROP TABLE trick_category');
        $this->addSql('DROP TABLE trick_comment');
        $this->addSql('DROP TABLE trick_picture');
        $this->addSql('DROP TABLE trick_video');
        $this->addSql('DROP TABLE user_info');
        $this->addSql('DROP TABLE user_picture');
        $this->addSql('DROP INDEX UNIQ_D8F0A91E5E237E06');
    }
}
