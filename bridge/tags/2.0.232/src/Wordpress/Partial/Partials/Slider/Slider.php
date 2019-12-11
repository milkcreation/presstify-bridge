<?php declare(strict_types=1);

namespace tiFy\Wordpress\Partial\Partials\Slider;

use tiFy\Partial\Partials\Slider\Slider as BaseSlider;
use tiFy\Wordpress\Contracts\Partial\PartialFactory as PartialFactoryContract;

class Slider extends BaseSlider implements PartialFactoryContract
{
    /**
     * @inheritDoc
     */
    public function boot(): void
    {
        add_action('init', function () {
            wp_register_style(
                'PartialSlider',
                asset()->url('partial/slider/css/styles.css'),
                ['slick', 'slick-theme'],
                170722
            );

            wp_register_script(
                'PartialSlider',
                asset()->url('partial/slider/js/scripts.js'),
                ['slick'],
                170722,
                true
            );
        });
    }

    /**
     * @inheritDoc
     */
    public function enqueue(): PartialFactoryContract
    {
        wp_enqueue_style('PartialSlider');
        wp_enqueue_script('PartialSlider');

        return $this;
    }
}