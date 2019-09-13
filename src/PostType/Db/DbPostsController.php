<?php

namespace tiFy\PostType\Db;

use tiFy\Db\DbFactory;

class DbPostsController extends DbFactory
{
    /**
     * @inheritdoc
     */
    public function defaults()
    {
        return array_merge(
            parent::defaults(),
            [
                'col_prefix' => 'post_',
                'columns'    => [
                    'ID'               => [
                        'auto_increment' => true,
                        'prefix'         => false,
                        'type'           => 'BIGINT',
                        'unsigned'       => true,
                        'size'           => 20,
                    ],
                    'author'           => [
                        'default'  => 0,
                        'type'     => 'BIGINT',
                        'unsigned' => true,
                        'size'     => 20,
                    ],
                    'date'             => [
                        'default' => '0000-00-00 00:00:00',
                        'type'    => 'DATETIME',
                    ],
                    'date_gmt'         => [
                        'default' => '0000-00-00 00:00:00',
                        'type'    => 'DATETIME',
                    ],
                    'content'          => [
                        'type' => 'LONGTEXT',
                    ],
                    'title'            => [
                        'type' => 'TEXT',
                    ],
                    'excerpt'          => [
                        'type' => 'TEXT',
                    ],
                    'status'           => [
                        'default' => 'publish',
                        'type'    => 'VARCHAR',
                        'size'    => 20,
                    ],
                    'comment_status'   => [
                        'default' => 'open',
                        'prefix'  => false,
                        'type'    => 'VARCHAR',
                        'size'    => 20,
                    ],
                    'ping_status'      => [
                        'default' => 'open',
                        'prefix'  => false,
                        'type'    => 'VARCHAR',
                        'size'    => 20,
                    ],
                    'password'         => [
                        'default' => '',
                        'type'    => 'VARCHAR',
                        'size'    => 20,
                    ],
                    'name'             => [
                        'default' => '',
                        'type'    => 'VARCHAR',
                        'size'    => 200,
                    ],
                    'to_ping'          => [
                        'prefix' => false,
                        'type'   => 'TEXT',
                    ],
                    'pinged'           => [
                        'prefix' => false,
                        'type'   => 'TEXT',
                    ],
                    'modified'         => [
                        'default' => '0000-00-00 00:00:00',
                        'type'    => 'DATETIME',
                    ],
                    'modified_gmt'     => [
                        'default' => '0000-00-00 00:00:00',
                        'type'    => 'DATETIME',
                    ],
                    'content_filtered' => [
                        'type' => 'LONGTEXT',
                    ],
                    'parent'           => [
                        'default'  => 0,
                        'type'     => 'BIGINT',
                        'unsigned' => true,
                        'size'     => 20,
                    ],
                    'guid'             => [
                        'default' => '',
                        'prefix'  => false,
                        'type'    => 'VARCHAR',
                        'size'    => 255,
                    ],
                    'menu_order'       => [
                        'default' => 0,
                        'prefix'  => false,
                        'type'    => 'INT',
                        'size'    => 11,
                    ],
                    'type'             => [
                        'default' => 'post',
                        'type'    => 'VARCHAR',
                        'size'    => 20,
                    ],
                    'mime_type'        => [
                        'default' => '',
                        'type'    => 'VARCHAR',
                        'size'    => 100,
                    ],
                    'comment_count'    => [
                        'default' => 0,
                        'prefix'  => false,
                        'type'    => 'BIGINT',
                        'size'    => 20,
                    ]
                ],
                'install'    => false,
                'name'       => 'posts',
                'meta'       => 'post',
                'search'     => [
                    'content',
                    'title',
                ]
            ]
        );
    }
}