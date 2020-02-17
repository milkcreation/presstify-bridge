<?php declare(strict_types=1);

namespace tiFy\Wordpress\Partial\Driver\ImageLightbox;

use tiFy\Partial\Driver\ImageLightbox\{ImageLightbox as BaseImageLightbox, ImageLightboxItem};
use tiFy\Contracts\Partial\ImageLightboxItem as ImageLightboxItemContract;
use tiFy\Validation\Validator as v;

class ImageLightbox extends BaseImageLightbox
{
    /**
     * @inheritDoc
     */
    public function fetchItem($item): ?ImageLightboxItemContract
    {
        if ($item instanceof ImageLightboxItemContract) {
            return $item;
        } elseif (is_array($item)) {
            if (isset($item['src']) && ($instance = $this->fetchItem($item['src']))) {
                unset($item['src']);
                return $instance->set($item);
            } elseif (isset($item['content'])) {
                return (new ImageLightboxItem())->set($item);
            } else {
                return null;
            }
        } elseif(is_numeric($item) && ($src = wp_get_attachment_url($item))) {
            return (new ImageLightboxItem())->set([
                'src' => $src
            ]);
        } elseif (is_string($item) && v::url()->validate($item)) {
            return (new ImageLightboxItem())->set([
                'src' => $item
            ]);
        } else {
            return null;
        }
    }
}