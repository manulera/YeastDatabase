<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191208162435 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE marker DROP FOREIGN KEY FK_82CF20FEF1DBDB2A');
        $this->addSql('DROP INDEX IDX_82CF20FEF1DBDB2A ON marker');
        $this->addSql('ALTER TABLE marker DROP plasmid_name');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE marker ADD plasmid_name VARCHAR(100) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE marker ADD CONSTRAINT FK_82CF20FEF1DBDB2A FOREIGN KEY (plasmid_name) REFERENCES plasmid (name)');
        $this->addSql('CREATE INDEX IDX_82CF20FEF1DBDB2A ON marker (plasmid_name)');
    }
}
