<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210218202444 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE calendar_category (calendar_id INT NOT NULL, category_id INT NOT NULL, INDEX IDX_32E39A9A40A2C8 (calendar_id), INDEX IDX_32E39A912469DE2 (category_id), PRIMARY KEY(calendar_id, category_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE calendar_tag (calendar_id INT NOT NULL, tag_id INT NOT NULL, INDEX IDX_FDE8D530A40A2C8 (calendar_id), INDEX IDX_FDE8D530BAD26311 (tag_id), PRIMARY KEY(calendar_id, tag_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE calendar_category ADD CONSTRAINT FK_32E39A9A40A2C8 FOREIGN KEY (calendar_id) REFERENCES calendar (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE calendar_category ADD CONSTRAINT FK_32E39A912469DE2 FOREIGN KEY (category_id) REFERENCES category (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE calendar_tag ADD CONSTRAINT FK_FDE8D530A40A2C8 FOREIGN KEY (calendar_id) REFERENCES calendar (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE calendar_tag ADD CONSTRAINT FK_FDE8D530BAD26311 FOREIGN KEY (tag_id) REFERENCES tag (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE calendar ADD url VARCHAR(255) DEFAULT NULL, DROP background_color, DROP border_color, DROP text_color');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE calendar_category');
        $this->addSql('DROP TABLE calendar_tag');
        $this->addSql('ALTER TABLE calendar ADD background_color VARCHAR(7) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, ADD border_color VARCHAR(7) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, ADD text_color VARCHAR(7) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, DROP url');
    }
}
