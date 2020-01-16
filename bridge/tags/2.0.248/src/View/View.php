<?php declare(strict_types=1);

namespace tiFy\View;

use Illuminate\Support\Traits\ForwardsCalls;
use Psr\Container\ContainerInterface as Container;
use tiFy\Contracts\View\{Engine as EngineContract, PlatesEngine as PlatesEngineContract, View as ViewContract};
use tiFy\View\Engine\Engine;
use LogicException;

/**
 * @mixin Engine
 */
class View implements ViewContract
{
    use ForwardsCalls;

    /**
     * Définition du répertoire par défaut des gabarits d'affichage.
     * @var string
     */
    protected static $defaultDirectory = '';

    /**
     * Instance du conteneur d'injection de dépendances.
     * @var Container|null
     */
    protected $container;

    /**
     * Instance du moteur de templates par défaut.
     * @var EngineContract|null
     */
    protected $default;

    /**
     * Instance des moteurs de templates déclarés.
     * @var EngineContract[]
     */
    protected $engines = [];

    /**
     * CONSTRUCTEUR
     *
     * @param Container|null $container
     *
     * @return void
     */
    public function __construct(?Container $container = null)
    {
        $this->container = $container;
    }

    /**
     * @inheritDoc
     */
    public static function setDefaultDirectory(string $dir): void
    {
        static::$defaultDirectory = $dir;
    }

    /**
     * @inheritDoc
     */
    public function __call(string $method, array $parameters)
    {
        return $this->forwardCallTo($this->getEngine(), $method, $parameters);
    }

    /**
     * @inheritDoc
     */
    public function getContainer(): ?Container
    {
        return $this->container;
    }

    /**
     * @inheritDoc
     */
    public function getDefaultDirectory(): string
    {
        return self::$defaultDirectory;
    }

    /**
     * @inheritDoc
     */
    public function getDefaultEngine(): EngineContract
    {
        return $this->getContainer()->get("view.engine.default");
    }

    /**
     * @inheritDoc
     */
    public function getEngine(?string $name = null): ?EngineContract
    {
        if (is_null($name)) {
            return $this->default = $this->default
                ? : $this->getDefaultEngine()
                    ->setParams(config('view', []))
                    ->setDirectory($this->getDefaultDirectory());
        }

        return $this->engines[$name] ?? null;
    }

    /**
     * @inheritDoc
     */
    public function getPlatesEngine(array $params = []): PlatesEngineContract
    {
        return $this->getContainer()->get("view.engine.plates")->setParams($params);
    }

    /**
     * @inheritDoc
     */
    public function make($name)
    {
        return $this->getEngine()->make($name);
    }

    /**
     * @inheritDoc
     */
    public function register(string $name, $attrs = null): EngineContract
    {
        if(is_array($attrs)) {
            if (isset($attrs['engine'])) {
                $engine = $this->getContainer()->has('view.engine.' . $attrs['engine'])
                    ? $this->getContainer()->get('view.engine.' . $attrs['engine']) : new $attrs['engine'];
                unset($attrs['engine']);
            } else {
                $engine = $this->getDefaultEngine();
            }
            $engine->params($attrs);
        } elseif (is_string($attrs)) {
            $engine = $this->getContainer()->has("view.engine.{$attrs}")
                ? $this->getContainer()->get("view.engine.{$attrs}") : new $attrs;
        } elseif(is_null($attrs)) {
            $engine = $this->getDefaultEngine();
        } else {
            $engine = $attrs;
        }

        if (!$engine instanceof EngineContract) {
            throw new LogicException(sprintf('Impossible de définir le moteur de template [%s]', $name));
        }

        return $this->engines[$name] = $engine;
    }

    /**
     * @inheritDoc
     */
    public function render($name, array $args = [])
    {
        return $this->getEngine()->render($name, $args);
    }

    /**
     * @inheritDoc
     */
    public function setEngine(string $name, EngineContract $engine): ViewContract
    {
        $this->engines[$name] = $engine;

        return $this;
    }
}