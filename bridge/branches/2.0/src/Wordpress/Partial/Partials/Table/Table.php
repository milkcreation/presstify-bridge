<?php declare(strict_types=1);

namespace tiFy\Wordpress\Partial\Partials\Table;

use tiFy\Partial\Partials\Table\Table as BaseTable;
use tiFy\Wordpress\Contracts\Partial\PartialFactory as PartialFactoryContract;

class Table extends BaseTable implements PartialFactoryContract
{
    /**
     * @inheritDoc
     */
    public function boot(): void
    {
        add_action('init', function () {
            wp_register_style(
                'PartialTable',
                asset()->url('partial/table/css/styles.css'),
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
        wp_enqueue_style('PartialTable');

        return $this;
    }
}