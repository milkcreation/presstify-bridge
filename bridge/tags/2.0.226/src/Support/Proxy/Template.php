<?php declare(strict_types=1);

namespace tiFy\Support\Proxy;

use tiFy\Contracts\Container\Container;
use tiFy\Contracts\Template\TemplateFactory;
use tiFy\Contracts\Template\TemplateManager;

/**
 * @method static array all()
 * @method static int count()
 * @method static TemplateFactory get(string $name)
 * @method static Container getContainer()
 * @method static TemplateManager register(string $name, array $attrs)
 * @method static TemplateManager set(string|array $key, $value = null)
 * @method static string resourcesDir(?string $path = '')
 * @method static string resourcesUrl(?string $path = '')
 */
class Template extends AbstractProxy
{
    public static function getInstanceIdentifier()
    {
        return 'template';
    }
}