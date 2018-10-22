<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20181020113111 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE event_game (event_id INT NOT NULL, game_id INT NOT NULL, INDEX IDX_3CE07D2771F7E88B (event_id), INDEX IDX_3CE07D27E48FD905 (game_id), PRIMARY KEY(event_id, game_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE game (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE result (id INT AUTO_INCREMENT NOT NULL, event_id INT DEFAULT NULL, game_id INT DEFAULT NULL, first_id INT DEFAULT NULL, second_id INT DEFAULT NULL, third_id INT DEFAULT NULL, fourth_id INT DEFAULT NULL, INDEX IDX_136AC11371F7E88B (event_id), INDEX IDX_136AC113E48FD905 (game_id), INDEX IDX_136AC113E84D625F (first_id), INDEX IDX_136AC113FF961BCC (second_id), INDEX IDX_136AC11374CCD3CA (third_id), INDEX IDX_136AC11353C18810 (fourth_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE event_game ADD CONSTRAINT FK_3CE07D2771F7E88B FOREIGN KEY (event_id) REFERENCES event (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE event_game ADD CONSTRAINT FK_3CE07D27E48FD905 FOREIGN KEY (game_id) REFERENCES game (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE result ADD CONSTRAINT FK_136AC11371F7E88B FOREIGN KEY (event_id) REFERENCES event (id)');
        $this->addSql('ALTER TABLE result ADD CONSTRAINT FK_136AC113E48FD905 FOREIGN KEY (game_id) REFERENCES game (id)');
        $this->addSql('ALTER TABLE result ADD CONSTRAINT FK_136AC113E84D625F FOREIGN KEY (first_id) REFERENCES event_player (id)');
        $this->addSql('ALTER TABLE result ADD CONSTRAINT FK_136AC113FF961BCC FOREIGN KEY (second_id) REFERENCES event_player (id)');
        $this->addSql('ALTER TABLE result ADD CONSTRAINT FK_136AC11374CCD3CA FOREIGN KEY (third_id) REFERENCES event_player (id)');
        $this->addSql('ALTER TABLE result ADD CONSTRAINT FK_136AC11353C18810 FOREIGN KEY (fourth_id) REFERENCES event_player (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE event_game DROP FOREIGN KEY FK_3CE07D27E48FD905');
        $this->addSql('ALTER TABLE result DROP FOREIGN KEY FK_136AC113E48FD905');
        $this->addSql('DROP TABLE event_game');
        $this->addSql('DROP TABLE game');
        $this->addSql('DROP TABLE result');
    }
}
