<?php declare(strict_types=1);

namespace tiFy\Support;

use ArgumentCountError;
use Exception;
use Psr\Container\ContainerInterface as Container;
use tiFy\Contracts\Support\Callback as CallbackContract;
use Closure;

class Callback implements CallbackContract
{
    /**
     * Liste des permissions de type de fonctions de rappel.
     * @var array
     */
    protected $permissions = [
        'closure'   => true,
        'function'  => true,
        'method'    => true,
        'invoke'    => true
    ];

    /**
     * Fonction de rappel.
     * @var callable
     */
    protected $callback;

    /**
     * Instance du conteneur d'injection de dépendances.
     * @var Container
     */
    protected $container;

    /**
     * Fonction de rappel de retour en cas d'échec.
     * @var callable
     */
    protected $fallback;

    /**
     * Type de fonction de rappel.
     * @var string closure|container|function|method|invokable
     */
    protected $type;

    /**
     * @inheritdoc
     */
    public static function make($callable, ...$args)
    {
        return (new static(null, [], null))
            ->set($callable)
            ->exec(...$args);
    }

    /**
     * Traitement d'une classe, d'une méthode de classe ou d'une fonction.
     *
     * @param Container|null $container Instance du conteneur d'injection de dépendances.
     * @param array $permissions Liste des permissions de traitement.
     * @param null|Closure $fallback Traitement de retour en cas d'échec.
     *
     * @return void
     */
    public function __construct(?Container $container = null, $permissions = [], ?Closure $fallback = null)
    {
        $this->container = $container;
        $this->permissions = array_merge($this->permissions, $permissions);
        $this->fallback = $fallback ?: null;
    }

    /**
     * Récupération de l'instance d'une classe selon som nom de qualification ou son alias de déclaration dans le
     * conteneur d'injection de dépendances.
     *
     * @param $class
     *
     * @return mixed
     *
     * @throws Exception
     */
    private function _resolve($class)
    {
        if (($this->getContainer() instanceof Container) && $this->getContainer()->has($class)) {
            return $this->getContainer()->get($class);
        }

        try {
            return new $class();
        } catch (ArgumentCountError $e) {
            throw new Exception($e->getMessage());
        }
    }

    /**
     * @inheritdoc
     */
    public function getContainer(): ?Container
    {
        return $this->container;
    }

    /**
     * @inheritdoc
     */
    public function exec(...$args)
    {
        return ! is_null($this->callback) && $this->isPermit()
            ? call_user_func_array($this->callback, $args)
            : call_user_func($this->fallback instanceof Closure ? $this->fallback : function (...$args) {
                return null;
            }, $args);
    }

    /**
     * @inheritdoc
     */
    public function isPermit() : bool
    {
        return $this->type && $this->permissions[$this->type];
    }

    /**
     * @inheritdoc
     */
    public function set($callable): CallbackContract
    {
        if (is_string($callable) && strpos($callable, '::') !== false) {
            $callable = explode('::', $callable);
            $this->type = 'method';
        } elseif (is_array($callable) && isset($callable[0]) && is_object($callable[0])) {
            $callable = [$callable[0], $callable[1]];
            $this->type = 'method';
        } elseif (is_array($callable) && isset($callable[0]) && is_string($callable[0])) {
            try {
                $callable = [$this->_resolve($callable[0]), $callable[1]];
            } catch (Exception $e) {
                return $this;
            }
            $this->type = 'method';
        } elseif (is_string($callable) && method_exists($callable, '__invoke')) {
            try {
                $callable = $this->_resolve($callable);
            } catch (Exception $e) {
                return $this;
            }
            $this->type = 'invoke';
        } elseif ($callable instanceof Closure) {
            $this->type = 'closure';
        } elseif (is_object($callable) && method_exists($callable, '__invoke')) {
            $this->type = 'invoke';
        } elseif (is_string($callable) && function_exists($callable)) {
            $this->type = 'function';
        }

        if (is_callable($callable)) {
            $this->callback = $callable;
        } else {
            $this->type = null;
        }

        return $this;
    }
}