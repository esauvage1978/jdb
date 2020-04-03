<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200403091014 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE project DROP FOREIGN KEY FK_2FB3D0EEEE45BDBF');
        $this->addSql('DROP TABLE picture');
        $this->addSql('DROP INDEX UNIQ_2FB3D0EEEE45BDBF ON project');
        $this->addSql('ALTER TABLE project ADD file_name VARCHAR(255) NOT NULL, ADD update_at DATETIME DEFAULT NULL, ADD file_extension VARCHAR(10) NOT NULL, DROP picture_id');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE picture (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, enable TINYINT(1) NOT NULL, file_name VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, update_at DATETIME DEFAULT NULL, file_extension VARCHAR(10) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, content LONGTEXT CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE project ADD picture_id INT DEFAULT NULL, DROP file_name, DROP update_at, DROP file_extension');
        $this->addSql('ALTER TABLE project ADD CONSTRAINT FK_2FB3D0EEEE45BDBF FOREIGN KEY (picture_id) REFERENCES picture (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_2FB3D0EEEE45BDBF ON project (picture_id)');
    }
}
