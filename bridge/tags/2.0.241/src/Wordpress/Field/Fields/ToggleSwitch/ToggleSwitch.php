<?php declare(strict_types=1);

namespace tiFy\Wordpress\Field\Fields\ToggleSwitch;

use tiFy\Field\Fields\ToggleSwitch\ToggleSwitch as BaseToggleSwitch;
use tiFy\Wordpress\Contracts\Field\FieldFactory as FieldFactoryContract;

class ToggleSwitch extends BaseToggleSwitch implements FieldFactoryContract
{
    /**
     * @inheritDoc
     */
    public function boot(): void
    {
        add_action('init', function () {
            wp_register_style(
                'FieldToggleSwitch',
                asset()->url('field/toggle-switch/css/styles.css'),
                [],
                170724
            );
            wp_register_script(
                'FieldToggleSwitch',
                asset()->url('field/toggle-switch/js/scripts.js'),
                ['jquery'],
                170724
            );
        });
    }

    /**
     * @inheritDoc
     */
    public function enqueue(): FieldFactoryContract
    {
        wp_enqueue_style('FieldToggleSwitch');
        wp_enqueue_script('FieldToggleSwitch');

        return $this;
    }
}