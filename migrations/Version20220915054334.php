<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220915054334 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE category (id INT AUTO_INCREMENT NOT NULL, title_category VARCHAR(50) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE formation (id INT AUTO_INCREMENT NOT NULL, title_formation VARCHAR(50) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE intern (id INT AUTO_INCREMENT NOT NULL, last_name VARCHAR(50) NOT NULL, first_name VARCHAR(50) NOT NULL, sex VARCHAR(1) NOT NULL, birth_date DATE NOT NULL, city VARCHAR(50) NOT NULL, mail VARCHAR(100) NOT NULL, phone_number VARCHAR(50) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE module (id INT AUTO_INCREMENT NOT NULL, category_id INT DEFAULT NULL, title_module VARCHAR(50) NOT NULL, INDEX IDX_C24262812469DE2 (category_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE session_cours (id INT AUTO_INCREMENT NOT NULL, sessions_id INT DEFAULT NULL, cours_id INT NOT NULL, period_day INT NOT NULL, INDEX IDX_26B7344AF17C4D8C (sessions_id), INDEX IDX_26B7344A7ECF78B0 (cours_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sessions (id INT AUTO_INCREMENT NOT NULL, formation_id INT DEFAULT NULL, starting_date DATE NOT NULL, ending_date DATE NOT NULL, max_period_day INT NOT NULL, reserved_places INT NOT NULL, INDEX IDX_9A609D135200282E (formation_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sessions_intern (sessions_id INT NOT NULL, intern_id INT NOT NULL, INDEX IDX_E04B4DF6F17C4D8C (sessions_id), INDEX IDX_E04B4DF6525DD4B4 (intern_id), PRIMARY KEY(sessions_id, intern_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE module ADD CONSTRAINT FK_C24262812469DE2 FOREIGN KEY (category_id) REFERENCES category (id)');
        $this->addSql('ALTER TABLE session_cours ADD CONSTRAINT FK_26B7344AF17C4D8C FOREIGN KEY (sessions_id) REFERENCES sessions (id)');
        $this->addSql('ALTER TABLE session_cours ADD CONSTRAINT FK_26B7344A7ECF78B0 FOREIGN KEY (cours_id) REFERENCES module (id)');
        $this->addSql('ALTER TABLE sessions ADD CONSTRAINT FK_9A609D135200282E FOREIGN KEY (formation_id) REFERENCES formation (id)');
        $this->addSql('ALTER TABLE sessions_intern ADD CONSTRAINT FK_E04B4DF6F17C4D8C FOREIGN KEY (sessions_id) REFERENCES sessions (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE sessions_intern ADD CONSTRAINT FK_E04B4DF6525DD4B4 FOREIGN KEY (intern_id) REFERENCES intern (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE module DROP FOREIGN KEY FK_C24262812469DE2');
        $this->addSql('ALTER TABLE session_cours DROP FOREIGN KEY FK_26B7344AF17C4D8C');
        $this->addSql('ALTER TABLE session_cours DROP FOREIGN KEY FK_26B7344A7ECF78B0');
        $this->addSql('ALTER TABLE sessions DROP FOREIGN KEY FK_9A609D135200282E');
        $this->addSql('ALTER TABLE sessions_intern DROP FOREIGN KEY FK_E04B4DF6F17C4D8C');
        $this->addSql('ALTER TABLE sessions_intern DROP FOREIGN KEY FK_E04B4DF6525DD4B4');
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP TABLE formation');
        $this->addSql('DROP TABLE intern');
        $this->addSql('DROP TABLE module');
        $this->addSql('DROP TABLE session_cours');
        $this->addSql('DROP TABLE sessions');
        $this->addSql('DROP TABLE sessions_intern');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
