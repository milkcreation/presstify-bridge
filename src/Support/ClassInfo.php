<?php declare(strict_types=1);

namespace tiFy\Support;

use BadMethodCallException;
use Exception;
use ReflectionClass;
use ReflectionException;
use tiFy\Contracts\Support\ClassInfo as ClassInfoContract;

/**
 * @mixin ReflectionClass
 */
class ClassInfo implements ClassInfoContract
{
    /**
     * Listes des classes.
     * @var ReflectionClass[]
     */
    static $classes = [];

    /**
     * Nom de qualification de la classe courante.
     * @var string
     */
    protected $classname = '';

    /**
     * CONSTRUCTEUR.
     *
     * @param string|object $class Nom complet ou instance de la classe.
     *
     * @return void
     *
     * @throws Exception
     */
    public function __construct($class)
    {
        $this->classname = is_object($class) ? get_class($class) : $class;

        if (!isset(self::$classes[$this->classname])) {
            try {
                self::$classes[$this->classname] = new ReflectionClass($this->classname);
            } catch (ReflectionException $e) {
                throw new Exception(
                    sprintf(
                        __('Récupération de la classe %s impossible. Erreur:%s', 'tify'),
                        $this->classname,
                        $e->getMessage()
                    ),
                    __('Classe indisponible', 'tify'),
                    $e->getCode()
                );
            }
        }

    }

    /**
     * @inheritdoc
     */
    public function __call($name, $arguments)
    {
        try {
            return self::$classes[$this->classname]->$name(...$arguments);
        } catch (Exception $e) {
            throw new BadMethodCallException(sprintf(__('La méthode %s n\'est pas disponible.', 'tify'), $name));
        }
    }

    /**
     * @inheritdoc
     */
    public function getDirname(): string
    {
        return dirname($this->getFilename());
    }

    /**
     * @inheritdoc
     */
    public function getKebabName(): string
    {
        return Str::kebab($this->getShortName());
    }

    /**
     * @inheritdoc
     */
    public function getRelPath(): string
    {
        return paths()->relPathFromBase($this->getDirname());
    }

    /**
     * @inheritdoc
     */
    public function getUrl(): string
    {
        return rtrim((string)url()->root($this->getRelPath()), '/');
    }
}