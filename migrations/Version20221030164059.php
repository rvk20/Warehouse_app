<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221030164059 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE product_properties ADD warehouse_id INT NOT NULL');
        $this->addSql('ALTER TABLE product_properties ADD CONSTRAINT FK_14A46EEC5080ECDE FOREIGN KEY (warehouse_id) REFERENCES warehouse (id)');
        $this->addSql('CREATE INDEX IDX_14A46EEC5080ECDE ON product_properties (warehouse_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE product_properties DROP FOREIGN KEY FK_14A46EEC5080ECDE');
        $this->addSql('DROP INDEX IDX_14A46EEC5080ECDE ON product_properties');
        $this->addSql('ALTER TABLE product_properties DROP warehouse_id');
    }
}
