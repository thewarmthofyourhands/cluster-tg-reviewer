<?php

declare(strict_types=1);

namespace Migrations;

use Eva\Database\Migrations\AbstractMigration;

class Migration1700554782 extends AbstractMigration
{
    public function up(): void
    {
        $sql = <<<EOF
        alter table telegramUser
            add constraint username_telegram_id_unique unique (username, telegramId);
        EOF;
        $this->execute($sql);
    }

    public function down(): void
    {
        $sql = <<<EOF
        alter table telegramUser
            drop constraint username_telegram_id_unique;
        EOF;
        $this->execute($sql);
    }
}
