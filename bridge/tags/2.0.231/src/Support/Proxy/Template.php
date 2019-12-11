<?php declare(strict_types=1);

namespace tiFy\Support\Proxy;

use tiFy\Contracts\Template\{TemplateFactory, TemplateManager};

/**
 * @method static array all()
 * @method static int count()
 * @method static TemplateFactory get(string $name)
 * @method static TemplateManager register(string $name, array $attrs)
 * @method static TemplateManager set(string|array $key, $value = null)
 * @method static string resourcesDir(?string $path = '')
 * @method static string resourcesUrl(?string $path = '')
 */
class Template extends AbstractProxy
{
    /**
     * {@inheritDoc}
     *
     * @return TemplateManager
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
        return 'template';
    }
}