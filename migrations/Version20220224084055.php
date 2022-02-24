<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220224084055 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE ordered_product ADD bill_id_id INT NOT NULL, ADD product_id_id INT NOT NULL');
        $this->addSql('ALTER TABLE ordered_product ADD CONSTRAINT FK_E6F097B649B4CBC9 FOREIGN KEY (bill_id_id) REFERENCES bill (id)');
        $this->addSql('ALTER TABLE ordered_product ADD CONSTRAINT FK_E6F097B6DE18E50B FOREIGN KEY (product_id_id) REFERENCES products (id)');
        $this->addSql('CREATE INDEX IDX_E6F097B649B4CBC9 ON ordered_product (bill_id_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_E6F097B6DE18E50B ON ordered_product (product_id_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE ordered_product DROP FOREIGN KEY FK_E6F097B649B4CBC9');
        $this->addSql('ALTER TABLE ordered_product DROP FOREIGN KEY FK_E6F097B6DE18E50B');
        $this->addSql('DROP INDEX IDX_E6F097B649B4CBC9 ON ordered_product');
        $this->addSql('DROP INDEX UNIQ_E6F097B6DE18E50B ON ordered_product');
        $this->addSql('ALTER TABLE ordered_product DROP bill_id_id, DROP product_id_id');
    }
}
