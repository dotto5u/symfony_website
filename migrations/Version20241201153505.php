<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241201153505 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE credit_card (id INT AUTO_INCREMENT NOT NULL, number VARCHAR(16) NOT NULL, expiration_date DATETIME NOT NULL, cvv VARCHAR(3) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE credit_card_user (credit_card_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_A06A22F97048FD0F (credit_card_id), INDEX IDX_A06A22F9A76ED395 (user_id), PRIMARY KEY(credit_card_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE credit_card_user ADD CONSTRAINT FK_A06A22F97048FD0F FOREIGN KEY (credit_card_id) REFERENCES credit_card (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE credit_card_user ADD CONSTRAINT FK_A06A22F9A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE credit_card_user DROP FOREIGN KEY FK_A06A22F97048FD0F');
        $this->addSql('ALTER TABLE credit_card_user DROP FOREIGN KEY FK_A06A22F9A76ED395');
        $this->addSql('DROP TABLE credit_card');
        $this->addSql('DROP TABLE credit_card_user');
    }
}
