<?php declare(strict_types=1);

namespace tiFy\Wordpress\Metabox\Drivers\Filefeed;

use tiFy\Metabox\Drivers\Filefeed\Filefeed as BaseFilefeed;

class Filefeed extends BaseFilefeed
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