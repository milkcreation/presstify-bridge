<?php declare(strict_types=1);

namespace tiFy\Wordpress\Field\Fields\Repeater;

use tiFy\Field\Fields\Repeater\Repeater as BaseRepeater;
use tiFy\Wordpress\Contracts\Field\FieldFactory as FieldFactoryContract;

class Repeater extends BaseRepeater implements FieldFactoryContract
{
    /**
     * @inheritDoc
     */
    public function boot(): void
    {
        parent::boot();

        add_action('init', function () {
            wp_register_style(
                'FieldRepeater',
                asset()->url('/field/repeater/css/styles.css'),
                [],
                170421
            );

            wp_register_script(
                'FieldRepeater',
                asset()->url('/field/repeater/js/scripts.js'),
                ['jquery', 'jquery-ui-widget', 'jquery-ui-sortable'],
                170421,
                true
            );
        });
    }

    /**
     * @inheritDoc
     */
    public function enqueue(): FieldFactoryContract
    {
        wp_enqueue_style('FieldRepeater');
        wp_enqueue_script('FieldRepeater');

        return $this;
    }
}