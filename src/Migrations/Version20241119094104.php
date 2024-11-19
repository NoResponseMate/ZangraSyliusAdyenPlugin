<?php

declare(strict_types=1);

namespace BitBag\SyliusAdyenPlugin\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241119094104 extends AbstractMigration
{
    public function getDescription(): string
    {
       return 'Add column token to log table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE bitbag_adyen_log ADD token VARCHAR(20) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE bitbag_adyen_log DROP token');
    }
}
