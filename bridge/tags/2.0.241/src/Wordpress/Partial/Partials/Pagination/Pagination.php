<?php declare(strict_types=1);

namespace tiFy\Wordpress\Partial\Partials\Pagination;

use tiFy\Partial\Partials\Pagination\Pagination as PaginationBase;
use tiFy\Wordpress\Contracts\Partial\PartialFactory as PartialFactoryContract;

class Pagination extends PaginationBase implements PartialFactoryContract
{
    /**
     * @inheritDoc
     */
    public function boot(): void
    {
        add_action('init', function () {
            wp_register_style(
                'PartialPagination',
                asset()->url('partial/pagination/css/styles.css'),
                [],
                181005
            );
        });
    }

    /**
     * @inheritDoc
     */
    public function enqueue(): PartialFactoryContract
    {
        wp_enqueue_style('PartialPagination');

        return $this;
    }
}