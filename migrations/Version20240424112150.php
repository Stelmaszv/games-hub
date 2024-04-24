<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240424112150 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE publisher_user (publisher_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_6C8A061E40C86FCE (publisher_id), INDEX IDX_6C8A061EA76ED395 (user_id), PRIMARY KEY(publisher_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE publisher_user ADD CONSTRAINT FK_6C8A061E40C86FCE FOREIGN KEY (publisher_id) REFERENCES publisher (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE publisher_user ADD CONSTRAINT FK_6C8A061EA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE publisher_user DROP FOREIGN KEY FK_6C8A061E40C86FCE');
        $this->addSql('ALTER TABLE publisher_user DROP FOREIGN KEY FK_6C8A061EA76ED395');
        $this->addSql('DROP TABLE publisher_user');
    }
}
