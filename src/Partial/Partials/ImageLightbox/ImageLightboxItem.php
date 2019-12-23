<?php declare(strict_types=1);

namespace tiFy\Partial\Partials\ImageLightbox;

use tiFy\Contracts\Partial\ImageLightboxItem as ImageLightboxItemContract;
use tiFy\Support\{HtmlAttrs, ParamsBag};

class ImageLightboxItem extends ParamsBag implements ImageLightboxItemContract
{
    /**
     * @inheritDoc
     */
    public function defaults(): array
    {
        return [
            'attrs'   => [],
            'after'   => '',
            'before'  => '',
            'caption' => '',
            'content' => null,
            'group'   => '',
            'src'     => '',
        ];
    }

    /**
     * @inheritDoc
     */
    public function getAttrs(bool $linearize = true)
    {
        return HtmlAttrs::createFromAttrs($this->get('attrs', []), $linearize);
    }

    /**
     * @inheritDoc
     */
    public function getContent(): string
    {
        $thumbnail = $this->get('content', null);

        if (is_null($thumbnail)) {
            return (string)partial('tag', [
                'tag'   => 'img',
                'attrs' => [
                    'src' => $this->get('src'),
                    'alt' => basename($this->get('src')),
                ],
            ]);
        } else {
            return is_callable($thumbnail) ? call_user_func($thumbnail) : (string)$thumbnail;
        }
    }

    /**
     * @inheritDoc
     */
    public function getGroup(): ?string
    {
        return $this->get('group');
    }

    /**
     * @inheritDoc
     */
    public function parse(): ImageLightboxItemContract
    {
        parent::parse();

        if ($caption = $this->pull('caption')) {
            $this->set('attrs.data-ilb2-caption', $caption);
        }

        if (!$this->get('attrs.href')) {
            $this->set('attrs.href', $this->get('src') ?: '#');
        }

        if ($group = $this->get('group')) {
            $this->set('attrs.data-group', $group);
        }

        $this->set('attrs.data-control', 'image-lightbox.item');

        return $this;
    }
}