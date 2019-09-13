<?php

use tiFy\PostType\Metabox\CustomHeader\CustomHeader;

return [
    'page@post_type' => [
        'existing_metabox_example' => [
            'content'   => CustomHeader::class,
            'context'   => 'tab'
        ],
        'closure_example' => [
            'title'     => 'Metabox closure example',
            'content'   => function (\WP_Post $post, $args) {
                return sprintf(
                    '%s %s %s on post: %s',
                    $args['arg1'],
                    $args['arg2'],
                    $args['arg3'],
                    $post->post_title
                );
            },
            'context'   => 'tab',
            'args'      => [
                'arg1' => 'Metabox',
                'arg2' => 'Closure',
                'arg3' => 'Example'
            ]
        ]
    ]
];