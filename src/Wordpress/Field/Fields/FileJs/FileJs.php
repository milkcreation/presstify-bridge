<?php declare(strict_types=1);

namespace tiFy\Wordpress\Field\Fields\FileJs;

use tiFy\Contracts\Field\FieldFactory as BaseFieldFactoryContract;
use tiFy\Field\Fields\FileJs\FileJs as BaseFileJs;
use tiFy\Support\Proxy\Router;
use tiFy\Wordpress\Contracts\Field\FieldFactory as FieldFactoryContract;

class FileJs extends BaseFileJs implements FieldFactoryContract
{
    /**
     * @inheritDoc
     */
    public function defaults(): array
    {
        return array_merge(parent::defaults(), [
            'dirname'  => WP_CONTENT_DIR . '/uploads',
        ]);
    }

    /**
     * @inheritDoc
     */
    public function setUrl(?string $url = null): BaseFieldFactoryContract
    {
        $this->url = is_null($url) ? Router::xhr(md5($this->getAlias()), [$this, 'xhrResponse'])->getUrl() : $url;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function enqueue(): FieldFactoryContract
    {
        return $this;
    }
}