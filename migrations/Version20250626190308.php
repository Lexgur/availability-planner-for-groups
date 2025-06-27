<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250626190308 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $table = $schema->createTable('user');
        $table->addColumn('uuid', 'string', ['length' => 36]);
        $table->addColumn('password', 'string', ['length' => 255]);
        $table->addColumn('is_verified', 'boolean');
        $table->addColumn('email_hash', 'string', ['length' => 64]);
        $table->addColumn('roles', 'json');
        $table->addColumn('created_at', 'datetime');
        $table->addColumn('updated_at', 'datetime');
        $table->setPrimaryKey(['uuid']);
        $table->addUniqueIndex(['email_hash'], 'UNIQ_IDENTIFIER_EMAIL_HASH');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            DROP TABLE user
        SQL);
    }
}
