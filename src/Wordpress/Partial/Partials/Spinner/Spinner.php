<?php declare(strict_types=1);

namespace tiFy\Wordpress\Partial\Partials\Spinner;

use tiFy\Partial\Partials\Spinner\Spinner as BaseSpinner;
use tiFy\Wordpress\Contracts\Partial\PartialFactory as PartialFactoryContract;

class Spinner extends BaseSpinner implements PartialFactoryContract
{
    /**
     * @inheritDoc
     */
    public function boot(): void
    {
        add_action('init', function () {
            wp_register_style(
                'PartialSpinner',
                asset()->url('partial/spinner/css/spinkit.min.css'),
                [],
                '1.2.5'
            );

            foreach ($this->spinners as $spinner) {
                wp_register_style(
                    "PartialSpinner-{$spinner}",
                    asset()->url("/partial/spinner/css/{$spinner}.min.css"),
                    [],
                    '1.2.5'
                );
            }
        });
    }

    /**
     * @inheritDoc
     */
    public function enqueue($spinner = null): PartialFactoryContract
    {
        if (!$spinner || !in_array($spinner, $this->spinners)) {
            wp_enqueue_style('PartialSpinner');
        } else {
            wp_enqueue_style("PartialSpinner-{$spinner}");
        }

        return $this;
    }
}