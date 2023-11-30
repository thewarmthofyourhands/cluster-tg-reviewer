<?php

declare(strict_types=1);

namespace App\ServiceProviders;

use Eva\Database\PDO\Connection;
use Eva\Database\ConnectionStoreInterface;
use Eva\DependencyInjection\ContainerInterface;

class DatabaseConnectionServiceProvider
{
    public function __construct(ConnectionStoreInterface $connectionStore, ContainerInterface $container)
    {
        $env = $container->getParameter('env');
        $connectionStore->add(
            'default',
            new Connection(
                $env['DB_HOST'],
                $env['DB_PORT'],
                $env['DB_NAME'],
                $env['DB_USERNAME'],
                $env['DB_PASSWORD'],
            ),
        );
    }
}
