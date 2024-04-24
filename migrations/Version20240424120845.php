<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240424120845 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE developer_publisher (developer_id INT NOT NULL, publisher_id INT NOT NULL, INDEX IDX_987299B764DD9267 (developer_id), INDEX IDX_987299B740C86FCE (publisher_id), PRIMARY KEY(developer_id, publisher_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE developer_publisher ADD CONSTRAINT FK_987299B764DD9267 FOREIGN KEY (developer_id) REFERENCES developer (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE developer_publisher ADD CONSTRAINT FK_987299B740C86FCE FOREIGN KEY (publisher_id) REFERENCES publisher (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE developer_publisher DROP FOREIGN KEY FK_987299B764DD9267');
        $this->addSql('ALTER TABLE developer_publisher DROP FOREIGN KEY FK_987299B740C86FCE');
        $this->addSql('DROP TABLE developer_publisher');
    }
}
