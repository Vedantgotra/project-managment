<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230430070656 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE project ADD teacher_id INT DEFAULT NULL, ADD description VARCHAR(255) NOT NULL, ADD technology VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE project ADD CONSTRAINT FK_2FB3D0EE41807E1D FOREIGN KEY (teacher_id) REFERENCES teacher (id)');
        $this->addSql('CREATE INDEX IDX_2FB3D0EE41807E1D ON project (teacher_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE project DROP FOREIGN KEY FK_2FB3D0EE41807E1D');
        $this->addSql('DROP INDEX IDX_2FB3D0EE41807E1D ON project');
        $this->addSql('ALTER TABLE project DROP teacher_id, DROP description, DROP technology');
    }
}
