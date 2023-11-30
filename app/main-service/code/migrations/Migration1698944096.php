<?php

declare(strict_types=1);

namespace Migrations;

use Eva\Database\Migrations\AbstractMigration;

class Migration1698944096 extends AbstractMigration
{
    public function up(): void
    {
        $sql = <<<EOF
        create table admins (
            id bigint null auto_increment primary key,
            nickname varchar(256) not null,
            messengerId bigint not null,
            messengerType varchar(256) not null
        );

        create table projects (
            id bigint null auto_increment primary key,
            adminId bigint not null,
            name varchar(512) not null,
            gitRepositoryUrl varchar(1024) not null,
            gitType varchar(256) not null,
            reviewType varchar(256) not null
        );

        create table chats (
            id bigint null auto_increment primary key,
            projectId bigint not null,
            messengerId bigint not null,
            messengerType varchar(256) not null,
            status varchar(256) not null
        );

        create table credentials (
            id bigint null auto_increment primary key,
            projectId bigint not null,
            token varchar(1024) not null,
            dateExpired datetime not null,
            isRequestWorkable bool not null default 0
        );

        create table developers (
            id bigint null auto_increment primary key,
            projectId bigint not null,
            nickname varchar(256) not null,
            isAdmin bool not null,
            status varchar(256) not null
        );

        create table pullRequests (
            id bigint null auto_increment primary key,
            projectId bigint not null,
            developerId bigint null,
            pullRequestNumber bigint null,
            title varchar(256) not null,
            branch varchar(256) not null,
            status varchar(256) not null,
            lastPendingDate varchar(256) null
        );

        EOF;

        $this->execute($sql);
    }

    public function down(): void
    {
        $sql = <<<EOF
        drop table developers;
        drop table chats;
        drop table credentials;
        drop table projects;
        drop table admins;
        drop table pullRequests;
        EOF;

        $this->execute($sql);
    }
}
