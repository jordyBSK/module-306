<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231218000000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create initial database schema for football shop';
    }

    public function up(Schema $schema): void
    {
        // Create products table
        $this->addSql('CREATE TABLE products (
            id SERIAL PRIMARY KEY,
            name VARCHAR(255) NOT NULL,
            description TEXT,
            price DECIMAL(10,2) NOT NULL,
            image VARCHAR(255),
            size VARCHAR(50) NOT NULL,
            team VARCHAR(100) NOT NULL,
            season VARCHAR(20) NOT NULL,
            stock INTEGER NOT NULL DEFAULT 0,
            active BOOLEAN NOT NULL DEFAULT true
        )');

        // Create cart_items table
        $this->addSql('CREATE TABLE cart_items (
            id SERIAL PRIMARY KEY,
            session_id VARCHAR(255) NOT NULL,
            product_id INTEGER NOT NULL,
            quantity INTEGER NOT NULL,
            created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
        )');

        // Create index on session_id for better performance
        $this->addSql('CREATE INDEX IDX_BEF48445D044D5D4 ON cart_items (session_id)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE IF EXISTS cart_items');
        $this->addSql('DROP TABLE IF EXISTS products');
    }
}