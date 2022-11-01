<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221101022414 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE product_state (id INT AUTO_INCREMENT NOT NULL, product_id INT NOT NULL, warehouse_id INT NOT NULL, quantity INT NOT NULL, INDEX IDX_2CFA9A74584665A (product_id), INDEX IDX_2CFA9A75080ECDE (warehouse_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE product_state ADD CONSTRAINT FK_2CFA9A74584665A FOREIGN KEY (product_id) REFERENCES product (id)');
        $this->addSql('ALTER TABLE product_state ADD CONSTRAINT FK_2CFA9A75080ECDE FOREIGN KEY (warehouse_id) REFERENCES warehouse (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE product_state DROP FOREIGN KEY FK_2CFA9A74584665A');
        $this->addSql('ALTER TABLE product_state DROP FOREIGN KEY FK_2CFA9A75080ECDE');
        $this->addSql('DROP TABLE product_state');
    }
}
