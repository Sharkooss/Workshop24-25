<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240924072004 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE buy (id INT AUTO_INCREMENT NOT NULL, id_users_id INT DEFAULT NULL, id_shop_id INT DEFAULT NULL, INDEX IDX_CF838277376858A8 (id_users_id), INDEX IDX_CF838277938B6DAD (id_shop_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE challenge (id INT AUTO_INCREMENT NOT NULL, name_challenge VARCHAR(255) NOT NULL, descritpion_challenge LONGTEXT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE challenged (id INT AUTO_INCREMENT NOT NULL, id_users_id INT DEFAULT NULL, id_challenge_id INT DEFAULT NULL, amount INT DEFAULT NULL, INDEX IDX_84662227376858A8 (id_users_id), INDEX IDX_84662227BB636FB4 (id_challenge_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE event (id INT AUTO_INCREMENT NOT NULL, id_users_id INT DEFAULT NULL, name_event VARCHAR(255) NOT NULL, description_event LONGTEXT DEFAULT NULL, INDEX IDX_3BAE0AA7376858A8 (id_users_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE friend (id INT AUTO_INCREMENT NOT NULL, id_users_id INT DEFAULT NULL, INDEX IDX_55EEAC61376858A8 (id_users_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE participate (id INT AUTO_INCREMENT NOT NULL, id_users_id INT DEFAULT NULL, id_event_id INT DEFAULT NULL, INDEX IDX_D02B138376858A8 (id_users_id), INDEX IDX_D02B138212C041E (id_event_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE shop (id INT AUTO_INCREMENT NOT NULL, name_shop VARCHAR(255) NOT NULL, description_shop VARCHAR(255) DEFAULT NULL, price_shop DOUBLE PRECISION NOT NULL, stock_shop INT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE users (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, surname_users VARCHAR(255) NOT NULL, point_users INT DEFAULT NULL, birth_users DATE DEFAULT NULL, role_users VARCHAR(255) NOT NULL, phone_users VARCHAR(20) DEFAULT NULL, email_users VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE buy ADD CONSTRAINT FK_CF838277376858A8 FOREIGN KEY (id_users_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE buy ADD CONSTRAINT FK_CF838277938B6DAD FOREIGN KEY (id_shop_id) REFERENCES shop (id)');
        $this->addSql('ALTER TABLE challenged ADD CONSTRAINT FK_84662227376858A8 FOREIGN KEY (id_users_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE challenged ADD CONSTRAINT FK_84662227BB636FB4 FOREIGN KEY (id_challenge_id) REFERENCES challenge (id)');
        $this->addSql('ALTER TABLE event ADD CONSTRAINT FK_3BAE0AA7376858A8 FOREIGN KEY (id_users_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE friend ADD CONSTRAINT FK_55EEAC61376858A8 FOREIGN KEY (id_users_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE participate ADD CONSTRAINT FK_D02B138376858A8 FOREIGN KEY (id_users_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE participate ADD CONSTRAINT FK_D02B138212C041E FOREIGN KEY (id_event_id) REFERENCES event (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE buy DROP FOREIGN KEY FK_CF838277376858A8');
        $this->addSql('ALTER TABLE buy DROP FOREIGN KEY FK_CF838277938B6DAD');
        $this->addSql('ALTER TABLE challenged DROP FOREIGN KEY FK_84662227376858A8');
        $this->addSql('ALTER TABLE challenged DROP FOREIGN KEY FK_84662227BB636FB4');
        $this->addSql('ALTER TABLE event DROP FOREIGN KEY FK_3BAE0AA7376858A8');
        $this->addSql('ALTER TABLE friend DROP FOREIGN KEY FK_55EEAC61376858A8');
        $this->addSql('ALTER TABLE participate DROP FOREIGN KEY FK_D02B138376858A8');
        $this->addSql('ALTER TABLE participate DROP FOREIGN KEY FK_D02B138212C041E');
        $this->addSql('DROP TABLE buy');
        $this->addSql('DROP TABLE challenge');
        $this->addSql('DROP TABLE challenged');
        $this->addSql('DROP TABLE event');
        $this->addSql('DROP TABLE friend');
        $this->addSql('DROP TABLE participate');
        $this->addSql('DROP TABLE shop');
        $this->addSql('DROP TABLE users');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
