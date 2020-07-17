<?php declare(strict_types=1);

namespace tiFy\Wordpress\Field\Driver\FileJs;

use tiFy\Contracts\Field\FieldDriver as BaseFieldDriverContract;
use tiFy\Field\Driver\FileJs\FileJs as BaseFileJs;
use tiFy\Support\Proxy\Router;
use tiFy\Wordpress\Contracts\Field\FieldDriver as FieldDriverContract;

class FileJs extends BaseFileJs implements FieldDriverContract
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
    public function setUrl(?string $url = null): BaseFieldDriverContract
    {
        $this->url = is_null($url) ? Router::xhr(md5($this->getAlias()), [$this, 'xhrResponse'])->getUrl() : $url;

        return $this;
    }
}