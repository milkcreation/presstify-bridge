<?php declare(strict_types=1);

namespace tiFy\Wordpress\Field\Fields\Colorpicker;

use tiFy\Field\Fields\Colorpicker\Colorpicker as BaseColorpicker;
use tiFy\Wordpress\Contracts\Field\FieldFactory as FieldFactoryContract;

class Colorpicker extends BaseColorpicker implements FieldFactoryContract
{
    /**
     * @inheritDoc
     */
    public function boot(): void
    {
        add_action('init', function () {
            wp_register_style(
                'FieldColorpicker',
                asset()->url('field/colorpicker/css/styles.css'),
                ['spectrum'],
                180725
            );

            $deps = ['jquery', 'spectrum'];
            if (wp_script_is('spectrum-i10n', 'registered')) {
                $deps[] = 'spectrum-i10n';
            }

            wp_register_script(
                'FieldColorpicker',
                asset()->url('field/colorpicker/js/scripts.js'),
                $deps,
                180725,
                true
            );
        });
    }

    /**
     * @inheritDoc
     */
    public function enqueue(): FieldFactoryContract
    {
        wp_enqueue_style('FieldColorpicker');
        wp_enqueue_script('FieldColorpicker');

        return $this;
    }
}