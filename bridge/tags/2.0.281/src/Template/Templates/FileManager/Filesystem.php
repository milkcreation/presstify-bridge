<?php declare(strict_types=1);

namespace tiFy\Template\Templates\FileManager;

use League\Flysystem\AdapterInterface;
use League\Flysystem\Adapter\Local;
use tiFy\Contracts\Template\FactoryAwareTrait as FactoryAwareTraitContract;
use tiFy\Contracts\Template\TemplateFactory;
use tiFy\Filesystem\Filesystem as tiFyFilesystem;
use tiFy\Template\Factory\FactoryAwareTrait;
use tiFy\Template\Templates\FileManager\Contracts\{FileManager, Filesystem as FilesystemContract};

class Filesystem extends tiFyFilesystem implements FilesystemContract
{
    use FactoryAwareTrait;

    /**
     * Instance du gabarit d'affichage.
     * @var FileManager
     */
    protected $factory;

    /**
     * Définition d'une instance.
     *
     * @param FileManager $factory
     *
     * @return static
     */
    public static function createFromFactory(FileManager $factory): FilesystemContract
    {
        $args = $factory->param('driver', []);
        $config = [
            'case_sensitive'  => false,
            'disable_asserts' => true
        ];

        if ($args instanceof AdapterInterface) {
            $adapter = $args;
        } else {
            if (is_string($args)) {
                $args = ['root' => $args];
            }
            $args = array_merge([
                'root'         => PUBLIC_PATH,
                'writeFlags'   => LOCK_EX,
                'linkHandling' => Local::SKIP_LINKS,
                'permissions'  => []
            ], $args);

            $adapter = new Local($args['root'], $args['writeFlags'], $args['linkHandling'], $args['permissions']);
        }

        return (new static($adapter, $config))->setTemplateFactory($factory);
    }

    /**
     * {@inheritDoc}
     *
     * @return FilesystemContract
     */
    public function setTemplateFactory(TemplateFactory $factory): FactoryAwareTraitContract
    {
        $this->factory = $factory;

        return $this;
    }
}