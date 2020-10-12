<?php declare(strict_types=1);

namespace tiFy\Wordpress\Database\Model;

use Illuminate\Database\Eloquent\Builder;
use tiFy\Database\Concerns\ColumnsAwareTrait;
use tiFy\Database\Concerns\ConnectionAwareTrait;
use tiFy\Wordpress\Database\Concerns\MetaFieldsAwareTrait;

/**
 * @mixin Builder
 */
class Attachment extends Post
{
    use ColumnsAwareTrait, ConnectionAwareTrait, MetaFieldsAwareTrait;

    /**
     * @var string
     */
    protected $postType = 'attachment';

    /**
     * @var array
     */
    protected $appends = [
        'title',
        'url',
        'type',
        'description',
        'caption',
        'alt',
    ];

    /**
     * @var array
     */
    protected static $aliases = [
        'title'       => 'post_title',
        'url'         => 'guid',
        'type'        => 'post_mime_type',
        'description' => 'post_content',
        'caption'     => 'post_excerpt',
        'alt'         => ['meta' => '_wp_attachment_image_alt'],
    ];
}