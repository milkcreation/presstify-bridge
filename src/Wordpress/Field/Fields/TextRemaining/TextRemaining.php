<?php declare(strict_types=1);

namespace tiFy\Wordpress\Field\Fields\TextRemaining;

use tiFy\Field\Fields\TextRemaining\TextRemaining as BaseTextRemaining;
use tiFy\Wordpress\Contracts\Field\FieldFactory as FieldFactoryContract;

class TextRemaining extends BaseTextRemaining implements FieldFactoryContract
{
    /**
     * @inheritDoc
     */
    public function boot(): void
    {
        add_action('init', function () {
            wp_register_style(
                'FieldTextRemaining',
                asset()->url('field/text-remaining/css/styles.css'),
                [],
                180611
            );
            wp_register_script(
                'FieldTextRemaining',
                asset()->url('field/text-remaining/js/scripts.js'),
                ['jquery'],
                180611,
                true
            );
        });
    }

    /**
     * @inheritDoc
     */
    public function enqueue(): FieldFactoryContract
    {
        wp_enqueue_style('FieldTextRemaining');
        wp_enqueue_script('FieldTextRemaining');

        return $this;
    }
}