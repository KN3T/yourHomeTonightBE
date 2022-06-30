<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220629075619 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE image (id INT AUTO_INCREMENT NOT NULL, path VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, deleted_at DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE hotel ADD deleted_at DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE hotel_image ADD image_id INT NOT NULL, DROP path, DROP created_at');
        $this->addSql('ALTER TABLE hotel_image ADD CONSTRAINT FK_26E9CA9B3DA5256D FOREIGN KEY (image_id) REFERENCES image (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_26E9CA9B3DA5256D ON hotel_image (image_id)');
        $this->addSql('ALTER TABLE room ADD deleted_at DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE room_image ADD image_id INT NOT NULL, DROP path, DROP created_at');
        $this->addSql('ALTER TABLE room_image ADD CONSTRAINT FK_8F81A5F43DA5256D FOREIGN KEY (image_id) REFERENCES image (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8F81A5F43DA5256D ON room_image (image_id)');
        $this->addSql('ALTER TABLE user ADD deleted_at DATETIME DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE hotel_image DROP FOREIGN KEY FK_26E9CA9B3DA5256D');
        $this->addSql('ALTER TABLE room_image DROP FOREIGN KEY FK_8F81A5F43DA5256D');
        $this->addSql('DROP TABLE image');
        $this->addSql('ALTER TABLE hotel DROP deleted_at');
        $this->addSql('DROP INDEX UNIQ_26E9CA9B3DA5256D ON hotel_image');
        $this->addSql('ALTER TABLE hotel_image ADD path VARCHAR(255) NOT NULL, ADD created_at DATETIME NOT NULL, DROP image_id');
        $this->addSql('ALTER TABLE room DROP deleted_at');
        $this->addSql('DROP INDEX UNIQ_8F81A5F43DA5256D ON room_image');
        $this->addSql('ALTER TABLE room_image ADD path VARCHAR(255) NOT NULL, ADD created_at DATETIME NOT NULL, DROP image_id');
        $this->addSql('ALTER TABLE `user` DROP deleted_at');
    }
}
