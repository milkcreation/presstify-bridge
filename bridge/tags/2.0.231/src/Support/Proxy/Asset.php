<?php declare(strict_types=1);

namespace tiFy\Support\Proxy;

use Psr\Container\ContainerInterface;
use tiFy\Contracts\Asset\Asset as AssetContract;

/**
 * @method static string footer()
 * @method static string header()
 * @method static ContainerInterface getContainer()
 * @method static string normalize(string $string)
 * @method static AssetContract setDataJs(string $key, mixed $value, $footer = false)
 * @method static AssetContract setInlineCss(string $css)
 * @method static AssetContract setInlineJs(string $js, bool $footer = false)
 * @method static string url(string $path)
 */
class Asset extends AbstractProxy
{
    /**
     * {@inheritDoc}
     *
     * @return AssetContract
     */
    public static function getInstance()
    {
        return parent::getInstance();
    }

    /**
     * @inheritDoc
     */
    public static function getInstanceIdentifier()
    {
        return 'asset';
    }
}