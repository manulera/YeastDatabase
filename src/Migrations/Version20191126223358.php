<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191126223358 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE tag (name VARCHAR(100) NOT NULL, color VARCHAR(100) NOT NULL, PRIMARY KEY(name)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE allele (id INT AUTO_INCREMENT NOT NULL, locus_name VARCHAR(100) DEFAULT NULL, tag_name VARCHAR(100) DEFAULT NULL, marker_name VARCHAR(100) DEFAULT NULL, name VARCHAR(255) NOT NULL, INDEX IDX_E5D4171C32D1C46 (locus_name), INDEX IDX_E5D4171CB02CC1B0 (tag_name), INDEX IDX_E5D4171CC77664AB (marker_name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE marker (name VARCHAR(100) NOT NULL, PRIMARY KEY(name)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE allele ADD CONSTRAINT FK_E5D4171C32D1C46 FOREIGN KEY (locus_name) REFERENCES locus (name)');
        $this->addSql('ALTER TABLE allele ADD CONSTRAINT FK_E5D4171CB02CC1B0 FOREIGN KEY (tag_name) REFERENCES tag (name)');
        $this->addSql('ALTER TABLE allele ADD CONSTRAINT FK_E5D4171CC77664AB FOREIGN KEY (marker_name) REFERENCES marker (name)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE allele DROP FOREIGN KEY FK_E5D4171CB02CC1B0');
        $this->addSql('ALTER TABLE allele DROP FOREIGN KEY FK_E5D4171CC77664AB');
        $this->addSql('DROP TABLE tag');
        $this->addSql('DROP TABLE allele');
        $this->addSql('DROP TABLE marker');
    }
}
