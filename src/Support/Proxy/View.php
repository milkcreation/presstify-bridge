<?php declare(strict_types=1);

namespace tiFy\Support\Proxy;

use League\Plates\Template\Folder;
use tiFy\Contracts\View\{Engine, PlatesEngine, View as ViewContract, PlatesFactory};

/**
 * @method static Engine addFolder(string $name, string $directory, bool $fallback = false)
 * @method static Folder|null getFolder(string $name)
 * @method static Engine getEngine(string $name = 'default')
 * @method static PlatesEngine getPlatesEngine(array $params = [])
 * @method static PlatesFactory make(string $view, array $args = [])
 * @method static Engine register(string $name, string|array|Engine|null $attrs = null)
 * @method static string render(string $view, array $args = [])
 * @method static string share(string|array $view, mixed $value = null)
 * @method static ViewContract setEngine(string $name, Engine $engine)
 */
class View extends AbstractProxy
{
    /**
     * {@inheritDoc}
     *
     * @return ViewContract
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
        return 'view';
    }
}