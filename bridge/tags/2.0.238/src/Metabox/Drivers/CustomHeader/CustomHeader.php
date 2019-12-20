<?php declare(strict_types=1);

namespace tiFy\Metabox\Drivers\CustomHeader;

use tiFy\Metabox\MetaboxDriver;

class CustomHeader extends MetaboxDriver
{
    /**
     * @inheritDoc
     */
    public function defaultParams(): array
    {
        return [
            'media_library_title'  => __('Personnalisation de l\'image d\'entête', 'tify'),
            'media_library_button' => __('Utiliser comme image d\'entête', 'tify'),
        ];
    }

    /**
     * @inheritDoc
     */
    public function defaults(): array
    {
        return array_merge(parent::defaults(), [
            'name'  => 'custom_header',
            'title' => __('Image d\'entête', 'tify'),
        ]);
    }
}