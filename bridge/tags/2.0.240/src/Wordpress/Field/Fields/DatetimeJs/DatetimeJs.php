<?php declare(strict_types=1);

namespace tiFy\Wordpress\Field\Fields\DatetimeJs;

use tiFy\Field\Fields\DatetimeJs\DatetimeJs as BaseDatetimeJs;
use tiFy\Wordpress\Contracts\Field\FieldFactory as FieldFactoryContract;

class DatetimeJs extends BaseDatetimeJs implements FieldFactoryContract
{
    /**
     * @inheritDoc
     */
    public function boot(): void
    {
        add_action('init', function () {
            wp_register_style(
                'FieldDatetimeJs',
                asset()->url('field/datetime-js/css/styles.css'),
                [],
                171112
            );
            wp_register_script(
                'FieldDatetimeJs',
                asset()->url('field/datetime-js/js/scripts.js'),
                ['jquery', 'moment'],
                171112,
                true
            );
        });
    }

    /**
     * @inheritDoc
     */
    public function enqueue(): FieldFactoryContract
    {
        wp_enqueue_style('FieldDatetimeJs');
        wp_enqueue_script('FieldDatetimeJs');

        return $this;
    }
}