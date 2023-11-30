<?php

declare(strict_types=1);

namespace Migrations;

use Eva\Database\Migrations\AbstractMigration;

class Migration1700555246 extends AbstractMigration
{
    public function up(): void
    {
        $sql = <<<EOF
        alter table admins
            add constraint nickname_messenger_id_messenger_type_unique unique (nickname, messengerId, messengerType);
        alter table developers
            add constraint project_id_nickname_unique unique (projectId, nickname);
        alter table projects
            add constraint admin_id_name_unique unique (adminId, name);
        EOF;
        $this->execute($sql);
    }

    public function down(): void
    {
        $sql = <<<EOF
        alter table admins
            drop constraint nickname_messenger_id_messenger_type_unique;
        alter table developers
            drop constraint project_id_nickname_unique;
        alter table projects
            drop constraint admin_id_name_unique;
        EOF;
        $this->execute($sql);
    }
}
