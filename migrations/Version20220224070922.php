<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220224070922 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) DEFAULT NULL, age INT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE ordered_product ADD id INT AUTO_INCREMENT NOT NULL, DROP product_id, DROP bill_id, DROP PRIMARY KEY, ADD PRIMARY KEY (id)');
        $this->addSql('ALTER TABLE products DROP FOREIGN KEY FK_B3BA5A5A9777D11E');
        $this->addSql('DROP INDEX IDX_B3BA5A5A9777D11E ON products');
        $this->addSql('ALTER TABLE products DROP category_id_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE user');
        $this->addSql('ALTER TABLE ordered_product MODIFY id INT NOT NULL');
        $this->addSql('ALTER TABLE ordered_product DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE ordered_product ADD product_id INT NOT NULL, ADD bill_id INT NOT NULL, DROP id');
        $this->addSql('ALTER TABLE ordered_product ADD PRIMARY KEY (product_id, bill_id)');
        $this->addSql('ALTER TABLE products ADD category_id_id INT NOT NULL');
        $this->addSql('ALTER TABLE products ADD CONSTRAINT FK_B3BA5A5A9777D11E FOREIGN KEY (category_id_id) REFERENCES category (id)');
        $this->addSql('CREATE INDEX IDX_B3BA5A5A9777D11E ON products (category_id_id)');
    }
}
