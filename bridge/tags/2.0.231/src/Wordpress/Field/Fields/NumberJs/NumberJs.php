<?php declare(strict_types=1);

namespace tiFy\Wordpress\Field\Fields\NumberJs;

use tiFy\Field\Fields\NumberJs\NumberJs as BaseNumberJs;
use tiFy\Wordpress\Contracts\Field\FieldFactory as FieldFactoryContract;

class NumberJs extends BaseNumberJs implements FieldFactoryContract
{
    /**
     * @inheritDoc
     */
    public function boot(): void
    {
        add_action('init', function () {
            wp_register_style(
                'FieldNumberJs',
                asset()->url('field/number-js/css/styles.css'),
                ['dashicons'],
                171019
            );
            wp_register_script(
                'FieldNumberJs',
                asset()->url('field/number-js/js/scripts.css'),
                ['jquery-ui-spinner'],
                171019,
                true
            );
        });
    }

    /**
     * @inheritDoc
     */
    public function enqueue(): FieldFactoryContract
    {
        wp_enqueue_style('FieldNumberJs');
        wp_enqueue_script('FieldNumberJs');

        return $this;
    }
}