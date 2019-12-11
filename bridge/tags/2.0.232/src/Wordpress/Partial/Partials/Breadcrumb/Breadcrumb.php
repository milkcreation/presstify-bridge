<?php declare(strict_types=1);

namespace tiFy\Wordpress\Partial\Partials\Breadcrumb;

use tiFy\Partial\Partials\Breadcrumb\Breadcrumb as BaseBreadcrumb;
use tiFy\Wordpress\Contracts\Partial\PartialFactory as PartialFactoryContract;

class Breadcrumb extends BaseBreadcrumb implements PartialFactoryContract
{
    /**
     * @inheritDoc
     */
    public function boot(): void
    {
        add_action('init', function () {
            wp_register_style(
                'PartialBreadcrumb',
                asset()->url('partial/breadcrumb/css/styles.css'),
                [],
                180122
            );
        });
    }

    /**
     * @inheritDoc
     */
    public function enqueue(): PartialFactoryContract
    {
        wp_enqueue_style('PartialBreadcrumb');

        return $this;
    }
}