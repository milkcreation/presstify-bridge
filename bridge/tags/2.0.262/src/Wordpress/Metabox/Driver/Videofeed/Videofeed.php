<?php declare(strict_types=1);

namespace tiFy\Wordpress\Metabox\Driver\Videofeed;

use tiFy\Metabox\Driver\Videofeed\Videofeed as BaseVideofeed;

class Videofeed extends BaseVideofeed
{
    /**
     * @inheritDoc
     */
    public function boot(): void
    {
        parent::boot();

        add_action('admin_enqueue_scripts', function() {
            @wp_enqueue_media();
        });
    }
}