<?php declare(strict_types=1);

namespace tiFy\Wordpress\Metabox\Driver\Imagefeed;

use tiFy\Metabox\Driver\Imagefeed\Imagefeed as BaseImagefeed;

class Imagefeed extends BaseImagefeed
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