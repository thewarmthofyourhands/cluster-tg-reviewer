<?php

declare(strict_types=1);

namespace Migrations;

use Eva\Database\Migrations\AbstractMigration;

class Migration1670359890 extends AbstractMigration
{
    public function up(): void
    {
        $sql = <<<EOF
        create table telegramUser (
            id bigint null auto_increment primary key,
            telegramId bigint not null,
            username varchar(256) not null,
            data text not null default '{"action": "default", "data": null}'
        );

        create table telegramTs (
            id int not null auto_increment primary key,
            value varchar(256) not null
        );
        EOF;

        $this->execute($sql);
    }

    public function down(): void
    {
        $sql = <<<EOF
        drop table telegramUser;
        drop table telegramTs;
        EOF;

        $this->execute($sql);
    }
}
