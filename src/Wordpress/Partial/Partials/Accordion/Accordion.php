<?php declare(strict_types=1);

namespace tiFy\Wordpress\Partial\Partials\Accordion;

use tiFy\Partial\Partials\Accordion\Accordion as BaseAccordion;
use tiFy\Wordpress\Contracts\Partial\PartialFactory as PartialFactoryContract;

class Accordion extends BaseAccordion implements PartialFactoryContract
{
    /**
     * @inheritDoc
     */
    public function boot(): void
    {
        add_action('init', function () {
            wp_register_style(
                'PartialAccordion',
                asset()->url('partial/accordion/css/styles.css'),
                [],
                181221
            );
            wp_register_script(
                'PartialAccordion',
                asset()->url('partial/accordion/js/scripts.js'),
                ['jquery-ui-widget'],
                181221,
                true
            );
        });
    }

    /**
     * @inheritDoc
     */
    public function enqueue(): PartialFactoryContract
    {
        wp_enqueue_style('PartialAccordion');
        wp_enqueue_script('PartialAccordion');

        return $this;
    }
}