<?php

namespace tiFy\User\Db;

use tiFy\Db\DbFactory;

class DbUsersController extends DbFactory
{
    /**
     * @inheritdoc
     */
    public function defaults()
    {
        return array_merge(
            parent::defaults(),
            [
                'col_prefix' => 'user_',
                'columns'    => [
                    'status'         => [
                        'default' => 0,
                        'type'    => 'INT',
                        'size'    => 11,
                    ],
                    'activation_key' => [
                        'default' => '',
                        'type'    => 'VARCHAR',
                        'size'    => 255,
                    ],
                    'display_name'   => [
                        'default' => '',
                        'prefix'  => false,
                        'type'    => 'VARCHAR',
                        'size'    => 250,
                    ],
                    'nicename'       => [
                        'default' => '',
                        'type'    => 'VARCHAR',
                        'size'    => 50,
                    ],
                    'spam'           => [
                        'default' => 0,
                        'prefix'  => false,
                        'type'    => 'TINYINT',
                        'size'    => 2,
                    ],
                    'url'            => [
                        'default' => '',
                        'type'    => 'VARCHAR',
                        'size'    => 100,
                    ],
                    'registered'     => [
                        'default' => '0000-00-00 00:00:00',
                        'type'    => 'DATETIME',
                    ],
                    'email'          => [
                        'default' => '',
                        'type'    => 'VARCHAR',
                        'size'    => 100,
                    ],
                    'deleted'        => [
                        'default' => 0,
                        'prefix'  => false,
                        'type'    => 'TINYINT',
                        'size'    => 2,
                    ],
                    'pass'           => [
                        'default' => '',
                        'type'    => 'VARCHAR',
                        'size'    => 255,
                    ],
                    'login'          => [
                        'default' => '',
                        'type'    => 'VARCHAR',
                        'size'    => 60,
                    ],
                    'ID'             => [
                        'auto_increment' => true,
                        'prefix'         => false,
                        'type'           => 'BIGINT',
                        'unsigned'       => true,
                        'size'           => 20,
                    ],
                ],
                'install'    => false,
                'meta'       => 'user'
            ]
        );
    }
}