<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210216145957 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE booking (id INT AUTO_INCREMENT NOT NULL, begin_at DATETIME NOT NULL, end_at DATETIME NOT NULL, title VARCHAR(255) NOT NULL, facebook_event VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE booking_category (booking_id INT NOT NULL, category_id INT NOT NULL, INDEX IDX_3D78874C3301C60 (booking_id), INDEX IDX_3D78874C12469DE2 (category_id), PRIMARY KEY(booking_id, category_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE booking_tag (booking_id INT NOT NULL, tag_id INT NOT NULL, INDEX IDX_25EC8E543301C60 (booking_id), INDEX IDX_25EC8E54BAD26311 (tag_id), PRIMARY KEY(booking_id, tag_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE booking_category ADD CONSTRAINT FK_3D78874C3301C60 FOREIGN KEY (booking_id) REFERENCES booking (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE booking_category ADD CONSTRAINT FK_3D78874C12469DE2 FOREIGN KEY (category_id) REFERENCES category (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE booking_tag ADD CONSTRAINT FK_25EC8E543301C60 FOREIGN KEY (booking_id) REFERENCES booking (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE booking_tag ADD CONSTRAINT FK_25EC8E54BAD26311 FOREIGN KEY (tag_id) REFERENCES tag (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE booking_category DROP FOREIGN KEY FK_3D78874C3301C60');
        $this->addSql('ALTER TABLE booking_tag DROP FOREIGN KEY FK_25EC8E543301C60');
        $this->addSql('DROP TABLE booking');
        $this->addSql('DROP TABLE booking_category');
        $this->addSql('DROP TABLE booking_tag');
    }
}
