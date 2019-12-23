<?php declare(strict_types=1);

namespace tiFy\Wordpress\Field\Fields\SelectImage;

use tiFy\Field\Fields\SelectImage\SelectImage as BaseSelectImage;
use tiFy\Wordpress\Contracts\Field\FieldFactory as FieldFactoryContract;

class SelectImage extends BaseSelectImage implements FieldFactoryContract
{
    /**
     * @inheritDoc
     */
    public function boot(): void
    {
        add_action('init', function () {
            wp_register_style(
                'FieldSelectImage',
                asset()->url('field/select-image/css/styles.css'),
                ['FieldSelectJs'],
                180808
            );
        });
    }

    /**
     * @inheritDoc
     */
    public function enqueue(): FieldFactoryContract
    {
        wp_enqueue_style('FieldSelectImage');
        wp_enqueue_script('FieldSelectJs');

        return $this;
    }
}