<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191102104347 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE event ADD sound VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE event_player CHANGE event_id event_id INT DEFAULT NULL, CHANGE player_id player_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE result CHANGE event_id event_id INT DEFAULT NULL, CHANGE game_id game_id INT DEFAULT NULL, CHANGE first_id first_id INT DEFAULT NULL, CHANGE second_id second_id INT DEFAULT NULL, CHANGE third_id third_id INT DEFAULT NULL, CHANGE fourth_id fourth_id INT DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE event DROP sound');
        $this->addSql('ALTER TABLE event_player CHANGE event_id event_id INT DEFAULT NULL, CHANGE player_id player_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE result CHANGE event_id event_id INT DEFAULT NULL, CHANGE game_id game_id INT DEFAULT NULL, CHANGE first_id first_id INT DEFAULT NULL, CHANGE second_id second_id INT DEFAULT NULL, CHANGE third_id third_id INT DEFAULT NULL, CHANGE fourth_id fourth_id INT DEFAULT NULL');
    }
}
