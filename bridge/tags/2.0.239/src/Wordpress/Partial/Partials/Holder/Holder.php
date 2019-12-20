<?php declare(strict_types=1);

namespace tiFy\Wordpress\Partial\Partials\Holder;

use tiFy\Partial\Partials\Holder\Holder as BaseHolder;
use tiFy\Wordpress\Contracts\Partial\PartialFactory as PartialFactoryContract;

class Holder extends BaseHolder implements PartialFactoryContract
{
    /**
     * @inheritDoc
     */
    public function boot(): void
    {
        add_action('init', function () {
            wp_register_style(
                'PartialHolder',
                asset()->url('partial/holder/css/styles.css'),
                [],
                160714
            );
        });
    }

    /**
     * @inheritDoc
     */
    public function enqueue(): PartialFactoryContract
    {
        wp_enqueue_style('PartialHolder');

        return $this;
    }
}