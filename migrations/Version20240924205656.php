<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240924205656 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE challenged DROP FOREIGN KEY FK_84662227376858A8');
        $this->addSql('DROP INDEX IDX_84662227376858A8 ON challenged');
        $this->addSql('ALTER TABLE challenged ADD challenger_id INT DEFAULT NULL, ADD opponent_id INT DEFAULT NULL, ADD selected_winner_challenger INT DEFAULT NULL, ADD selected_winner_opponent INT DEFAULT NULL, DROP id_users_id, DROP selected_winner');
        $this->addSql('ALTER TABLE challenged ADD CONSTRAINT FK_846622272D521FDF FOREIGN KEY (challenger_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE challenged ADD CONSTRAINT FK_846622277F656CDC FOREIGN KEY (opponent_id) REFERENCES users (id)');
        $this->addSql('CREATE INDEX IDX_846622272D521FDF ON challenged (challenger_id)');
        $this->addSql('CREATE INDEX IDX_846622277F656CDC ON challenged (opponent_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE challenged DROP FOREIGN KEY FK_846622272D521FDF');
        $this->addSql('ALTER TABLE challenged DROP FOREIGN KEY FK_846622277F656CDC');
        $this->addSql('DROP INDEX IDX_846622272D521FDF ON challenged');
        $this->addSql('DROP INDEX IDX_846622277F656CDC ON challenged');
        $this->addSql('ALTER TABLE challenged ADD id_users_id INT DEFAULT NULL, ADD selected_winner INT DEFAULT NULL, DROP challenger_id, DROP opponent_id, DROP selected_winner_challenger, DROP selected_winner_opponent');
        $this->addSql('ALTER TABLE challenged ADD CONSTRAINT FK_84662227376858A8 FOREIGN KEY (id_users_id) REFERENCES users (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_84662227376858A8 ON challenged (id_users_id)');
    }
}
