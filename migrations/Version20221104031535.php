<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221104031535 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE file (id INT AUTO_INCREMENT NOT NULL, productproperties_id INT NOT NULL, filename VARCHAR(255) NOT NULL, INDEX IDX_8C9F3610E16E9108 (productproperties_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE membership_warehouse (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, warehouse_id INT NOT NULL, INDEX IDX_567F5835A76ED395 (user_id), INDEX IDX_567F58355080ECDE (warehouse_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, unit VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product_properties (id INT AUTO_INCREMENT NOT NULL, product_id INT NOT NULL, warehouse_id INT NOT NULL, quantity INT NOT NULL, vat DOUBLE PRECISION NOT NULL, price DOUBLE PRECISION NOT NULL, INDEX IDX_14A46EEC4584665A (product_id), INDEX IDX_14A46EEC5080ECDE (warehouse_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product_state (id INT AUTO_INCREMENT NOT NULL, product_id INT NOT NULL, warehouse_id INT NOT NULL, quantity INT NOT NULL, INDEX IDX_2CFA9A74584665A (product_id), INDEX IDX_2CFA9A75080ECDE (warehouse_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_8D93D649F85E0677 (username), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE warehouse (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE file ADD CONSTRAINT FK_8C9F3610E16E9108 FOREIGN KEY (productproperties_id) REFERENCES product_properties (id)');
        $this->addSql('ALTER TABLE membership_warehouse ADD CONSTRAINT FK_567F5835A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE membership_warehouse ADD CONSTRAINT FK_567F58355080ECDE FOREIGN KEY (warehouse_id) REFERENCES warehouse (id)');
        $this->addSql('ALTER TABLE product_properties ADD CONSTRAINT FK_14A46EEC4584665A FOREIGN KEY (product_id) REFERENCES product (id)');
        $this->addSql('ALTER TABLE product_properties ADD CONSTRAINT FK_14A46EEC5080ECDE FOREIGN KEY (warehouse_id) REFERENCES warehouse (id)');
        $this->addSql('ALTER TABLE product_state ADD CONSTRAINT FK_2CFA9A74584665A FOREIGN KEY (product_id) REFERENCES product (id)');
        $this->addSql('ALTER TABLE product_state ADD CONSTRAINT FK_2CFA9A75080ECDE FOREIGN KEY (warehouse_id) REFERENCES warehouse (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE file DROP FOREIGN KEY FK_8C9F3610E16E9108');
        $this->addSql('ALTER TABLE membership_warehouse DROP FOREIGN KEY FK_567F5835A76ED395');
        $this->addSql('ALTER TABLE membership_warehouse DROP FOREIGN KEY FK_567F58355080ECDE');
        $this->addSql('ALTER TABLE product_properties DROP FOREIGN KEY FK_14A46EEC4584665A');
        $this->addSql('ALTER TABLE product_properties DROP FOREIGN KEY FK_14A46EEC5080ECDE');
        $this->addSql('ALTER TABLE product_state DROP FOREIGN KEY FK_2CFA9A74584665A');
        $this->addSql('ALTER TABLE product_state DROP FOREIGN KEY FK_2CFA9A75080ECDE');
        $this->addSql('DROP TABLE file');
        $this->addSql('DROP TABLE membership_warehouse');
        $this->addSql('DROP TABLE product');
        $this->addSql('DROP TABLE product_properties');
        $this->addSql('DROP TABLE product_state');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE warehouse');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
