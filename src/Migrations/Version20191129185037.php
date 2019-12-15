<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191129185037 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE plasmid (name VARCHAR(100) NOT NULL, file VARCHAR(255) DEFAULT NULL, PRIMARY KEY(name)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE strain_source (id INT AUTO_INCREMENT NOT NULL, type VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE deletion_bahler_method (id INT NOT NULL, plasmid_name VARCHAR(100) DEFAULT NULL, primerForward_name VARCHAR(100) DEFAULT NULL, primerReverse_name VARCHAR(100) DEFAULT NULL, INDEX IDX_769E722FDDDCC7B0 (primerForward_name), INDEX IDX_769E722F24AF6DE3 (primerReverse_name), INDEX IDX_769E722FF1DBDB2A (plasmid_name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE oligo (name VARCHAR(100) NOT NULL, sequence VARCHAR(255) NOT NULL, PRIMARY KEY(name)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE strain_allele (strain_id INT NOT NULL, allele_id INT NOT NULL, INDEX IDX_347C2A869B9E007 (strain_id), INDEX IDX_347C2A8F7400D9A (allele_id), PRIMARY KEY(strain_id, allele_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE deletion_bahler_method ADD CONSTRAINT FK_769E722FDDDCC7B0 FOREIGN KEY (primerForward_name) REFERENCES oligo (name)');
        $this->addSql('ALTER TABLE deletion_bahler_method ADD CONSTRAINT FK_769E722F24AF6DE3 FOREIGN KEY (primerReverse_name) REFERENCES oligo (name)');
        $this->addSql('ALTER TABLE deletion_bahler_method ADD CONSTRAINT FK_769E722FF1DBDB2A FOREIGN KEY (plasmid_name) REFERENCES plasmid (name)');
        $this->addSql('ALTER TABLE deletion_bahler_method ADD CONSTRAINT FK_769E722FBF396750 FOREIGN KEY (id) REFERENCES strain_source (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE strain_allele ADD CONSTRAINT FK_347C2A869B9E007 FOREIGN KEY (strain_id) REFERENCES strain (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE strain_allele ADD CONSTRAINT FK_347C2A8F7400D9A FOREIGN KEY (allele_id) REFERENCES allele (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE mol_biol ADD input_strain_id INT NOT NULL, ADD mol_biol_type VARCHAR(20) NOT NULL, DROP type, CHANGE id id INT NOT NULL');
        $this->addSql('ALTER TABLE mol_biol ADD CONSTRAINT FK_E60F54082B9009BF FOREIGN KEY (input_strain_id) REFERENCES strain (id)');
        $this->addSql('ALTER TABLE mol_biol ADD CONSTRAINT FK_E60F5408BF396750 FOREIGN KEY (id) REFERENCES strain_source (id) ON DELETE CASCADE');
        $this->addSql('CREATE INDEX IDX_E60F54082B9009BF ON mol_biol (input_strain_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE deletion_bahler_method DROP FOREIGN KEY FK_769E722FF1DBDB2A');
        $this->addSql('ALTER TABLE mol_biol DROP FOREIGN KEY FK_E60F5408BF396750');
        $this->addSql('ALTER TABLE deletion_bahler_method DROP FOREIGN KEY FK_769E722FBF396750');
        $this->addSql('ALTER TABLE deletion_bahler_method DROP FOREIGN KEY FK_769E722FDDDCC7B0');
        $this->addSql('ALTER TABLE deletion_bahler_method DROP FOREIGN KEY FK_769E722F24AF6DE3');
        $this->addSql('DROP TABLE plasmid');
        $this->addSql('DROP TABLE strain_source');
        $this->addSql('DROP TABLE deletion_bahler_method');
        $this->addSql('DROP TABLE oligo');
        $this->addSql('DROP TABLE strain_allele');
        $this->addSql('ALTER TABLE mol_biol DROP FOREIGN KEY FK_E60F54082B9009BF');
        $this->addSql('DROP INDEX IDX_E60F54082B9009BF ON mol_biol');
        $this->addSql('ALTER TABLE mol_biol ADD type VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, DROP input_strain_id, DROP mol_biol_type, CHANGE id id INT AUTO_INCREMENT NOT NULL');
    }
}
