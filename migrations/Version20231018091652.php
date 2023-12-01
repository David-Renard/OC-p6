<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231018091652 extends AbstractMigration
{


    public function getDescription(): string
    {
        return '';

    }

    public function up(Schema $schema): void
    {
        // This up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE trick ADD category_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE trick ADD author_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE trick ADD CONSTRAINT FK_D8F0A91E12469DE2 FOREIGN KEY (category_id) REFERENCES trick_category (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE trick ADD CONSTRAINT FK_D8F0A91EF675F31B FOREIGN KEY (author_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_D8F0A91E12469DE2 ON trick (category_id)');
        $this->addSql('CREATE INDEX IDX_D8F0A91EF675F31B ON trick (author_id)');
        $this->addSql('ALTER TABLE trick_comment ADD trick_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE trick_comment ADD comment_author_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE trick_comment ADD CONSTRAINT FK_F7292B34B281BE2E FOREIGN KEY (trick_id) REFERENCES trick (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE trick_comment ADD CONSTRAINT FK_F7292B341F0B124D FOREIGN KEY (comment_author_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_F7292B34B281BE2E ON trick_comment (trick_id)');
        $this->addSql('CREATE INDEX IDX_F7292B341F0B124D ON trick_comment (comment_author_id)');
        $this->addSql('ALTER TABLE trick_picture ADD trick_id INT NOT NULL');
        $this->addSql('ALTER TABLE trick_picture ADD CONSTRAINT FK_758636D1B281BE2E FOREIGN KEY (trick_id) REFERENCES trick (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_758636D1B281BE2E ON trick_picture (trick_id)');
        $this->addSql('ALTER TABLE trick_video ADD trick_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE trick_video ADD CONSTRAINT FK_B7E8DA93B281BE2E FOREIGN KEY (trick_id) REFERENCES trick (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_B7E8DA93B281BE2E ON trick_video (trick_id)');
        $this->addSql('ALTER TABLE user_picture ADD user_info_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user_picture ADD CONSTRAINT FK_4ED65183586DFF2 FOREIGN KEY (user_info_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_4ED65183586DFF2 ON user_picture (user_info_id)');
    }

    public function down(Schema $schema): void
    {
        // This down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE trick_video DROP CONSTRAINT FK_B7E8DA93B281BE2E');
        $this->addSql('DROP INDEX IDX_B7E8DA93B281BE2E');
        $this->addSql('ALTER TABLE trick_video DROP trick_id');
        $this->addSql('ALTER TABLE user_picture DROP CONSTRAINT FK_4ED65183586DFF2');
        $this->addSql('DROP INDEX UNIQ_4ED65183586DFF2');
        $this->addSql('ALTER TABLE user_picture DROP user_info_id');
        $this->addSql('ALTER TABLE trick_comment DROP CONSTRAINT FK_F7292B34B281BE2E');
        $this->addSql('ALTER TABLE trick_comment DROP CONSTRAINT FK_F7292B341F0B124D');
        $this->addSql('DROP INDEX IDX_F7292B34B281BE2E');
        $this->addSql('DROP INDEX IDX_F7292B341F0B124D');
        $this->addSql('ALTER TABLE trick_comment DROP trick_id');
        $this->addSql('ALTER TABLE trick_comment DROP comment_author_id');
        $this->addSql('ALTER TABLE trick DROP CONSTRAINT FK_D8F0A91E12469DE2');
        $this->addSql('ALTER TABLE trick DROP CONSTRAINT FK_D8F0A91EF675F31B');
        $this->addSql('DROP INDEX IDX_D8F0A91E12469DE2');
        $this->addSql('DROP INDEX IDX_D8F0A91EF675F31B');
        $this->addSql('ALTER TABLE trick DROP category_id');
        $this->addSql('ALTER TABLE trick DROP author_id');
        $this->addSql('ALTER TABLE trick_picture DROP CONSTRAINT FK_758636D1B281BE2E');
        $this->addSql('DROP INDEX IDX_758636D1B281BE2E');
        $this->addSql('ALTER TABLE trick_picture DROP trick_id');
    }
}
