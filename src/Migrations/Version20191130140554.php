<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191130140554 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE strain ADD source_id INT NOT NULL');
        $this->addSql('ALTER TABLE strain ADD CONSTRAINT FK_A630CD72953C1C61 FOREIGN KEY (source_id) REFERENCES strain_source (id)');
        $this->addSql('CREATE INDEX IDX_A630CD72953C1C61 ON strain (source_id)');
        $this->addSql('ALTER TABLE marker ADD plasmid_name VARCHAR(100) DEFAULT NULL');
        $this->addSql('ALTER TABLE marker ADD CONSTRAINT FK_82CF20FEF1DBDB2A FOREIGN KEY (plasmid_name) REFERENCES plasmid (name)');
        $this->addSql('CREATE INDEX IDX_82CF20FEF1DBDB2A ON marker (plasmid_name)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE marker DROP FOREIGN KEY FK_82CF20FEF1DBDB2A');
        $this->addSql('DROP INDEX IDX_82CF20FEF1DBDB2A ON marker');
        $this->addSql('ALTER TABLE marker DROP plasmid_name');
        $this->addSql('ALTER TABLE strain DROP FOREIGN KEY FK_A630CD72953C1C61');
        $this->addSql('DROP INDEX IDX_A630CD72953C1C61 ON strain');
        $this->addSql('ALTER TABLE strain DROP source_id');
    }
}
