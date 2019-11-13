<?php declare(strict_types=1);

namespace tiFy\Metabox;

use Exception;
use Illuminate\Support\Collection;
use InvalidArgumentException;
use Psr\Container\ContainerInterface as Container;
use tiFy\Contracts\Metabox\{MetaboxContext, MetaboxDriver, MetaboxManager as MetaboxManagerContract, MetaboxScreen};

class MetaboxManager implements MetaboxManagerContract
{
    /**
     * Instance du conteneur d'injection de dépendances.
     * @var Container
     */
    protected $container;

    /**
     * Liste des instances de contexte d'affichage.
     * @var MetaboxContext[]
     */
    protected $contexts = [];

    /**
     * Liste des instances de boîtes de saisie.
     * @var MetaboxDriver[]
     */
    protected $metaboxes = [];

    /**
     * Liste des instances des écrans d'affichage.
     * @var MetaboxScreen[]
     */
    protected $screens = [];

    /**
     * Liste des instances des boîtes de saisie par contexte d'affichage.
     * @var MetaboxDriver[][]
     */
    protected $renderItems = [];

    /**
     * @inheritDoc
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * @inheritDoc
     */
    public function add(string $alias, $item = []): MetaboxDriver
    {
        $attrs = [];

        if (isset($this->metaboxes[$alias])) {
            throw new InvalidArgumentException(sprintf(
                __('L\'alias de qualification [%s] est déjà utilisé par une autre boîte de saisie.', 'tify'), $alias
            ));
        }

        if (is_array($item)) {
            $driver = $item['driver'] ?? '';
            unset($item['driver']);
            $attrs = $item;
        } else {
            $driver = $item;
        }

        if (!$driver) {
            $driver = $this->getContainer()->get(MetaboxDriver::class);
        }

        if (is_object($driver)) {
            $metabox = $driver;
        } elseif (class_exists($driver)) {
            $metabox = new $driver();
        } else {
            try {
                $metabox = $this->getDriver($driver);
            } catch (Exception $e) {
                throw new InvalidArgumentException(
                    sprintf(__('Impossible de définir le pilote associé à la boîte de saisie [%s].', 'tify'), $alias)
                );
            }
        }

        $metabox->setManager($this)->boot();

        return $this->metaboxes[$alias] = $metabox->set($attrs)->parse();
    }

    /**
     * @inheritDoc
     */
    public function addScreen(string $name, $screen = []): MetaboxScreen
    {
        if (!$screen instanceof MetaboxScreen) {
            $screen = $this->getContainer()->get(MetaboxScreen::class);
        }

        return $this->screens[$name] = $screen->setManager($this)->setName($name);
    }

    /**
     * @inheritDoc
     */
    public function all(): array
    {
        return $this->metaboxes;
    }

    /**
     * @inheritDoc
     */
    public function fetchRender(MetaboxContext $context, ?MetaboxScreen $screen = null): array
    {
        if (is_null($screen)) {
            $screens = (new Collection($this->screens))->filter(function (MetaboxScreen $screen) {
                return $screen->isCurrent();
            })->all();
        } else {
            $screens = [$screen];
        }

        return (new Collection($this->metaboxes))->filter(function (MetaboxDriver $box) use ($context, $screens) {
            return ($box->context() === $context) && in_array($box->screen(), array_values($screens));
        })->all();
    }

    /**
     * @inheritDoc
     */
    public function getContainer(): Container
    {
        return $this->container;
    }

    /**
     * @inheritDoc
     */
    public function getContext(string $name): MetaboxContext
    {
        if (!isset($this->contexts[$name])) {
            if ($context = $this->getContainer()->get("metabox.context.{$name}")) {
                $this->contexts[$name] = $context->setManager($this)->setName($name);
            } else {
                throw new InvalidArgumentException(
                    sprintf(__('Le contexte d\'affichage [%s] de boîte de saisie n\'est pas déclaré.', 'tify'), $name)
                );
            }
        }

        return $this->contexts[$name];
    }

    /**
     * @inheritDoc
     */
    public function getDriver(string $name): MetaboxDriver
    {
        if ($driver = $this->getContainer()->get("metabox.driver.{$name}")) {
            return $driver;
        } else {
            throw new InvalidArgumentException(
                sprintf(__('Le pilote [%s] de boîte de saisie n\'est pas déclaré.', 'tify'), $name)
            );
        }
    }

    /**
     * @inheritDoc
     */
    public function getRenderItems(string $context): array
    {
        return $this->renderItems[$context] ?? [];
    }

    /**
     * @inheritDoc
     */
    public function getScreen(string $name): ?MetaboxScreen
    {
        return $this->screens[$name] ?? null;
    }

    /**
     * @inheritDoc
     */
    public function registerContext(string $name, MetaboxContext $context): MetaboxManagerContract
    {
        if (!$this->getContainer()->has("metabox.context.{$name}")) {
            $this->getContainer()->add("metabox.context.{$name}", $context->setManager($this)->setName($name));
        } else {
            throw new InvalidArgumentException(sprintf(__(
                'Le nom de qualification [%s] est déjà utilisé par un autre contexte d\'affichage de boîte de saisie.',
                'tify'
            ), $name));
        }

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function registerDriver(string $name, MetaboxDriver $driver): MetaboxManagerContract
    {
        if (!$this->getContainer()->has("metabox.driver.{$name}")) {
            $this->getContainer()->add("metabox.driver.{$name}", $driver->setManager($this));
        } else {
            throw new InvalidArgumentException(sprintf(__(
                'Le nom de qualification [%s] est déjà utilisé par un autre pilote de boîte de saisie.',
                'tify'
            ), $name));
        }

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function render(string $context, $args = []): string
    {
        if ($ctx = $this->getContext($context)) {
            if ($this->renderItems[$context] = $items = $this->fetchRender($ctx, null)) {
                foreach ($items as $item) {
                    $item->handle($args);
                }
            }

            return $ctx->render();
        } else {
            return '';
        }
    }

    /**
     * @inheritDoc
     */
    public function resourcesDir(string $path = ''): string
    {
        $path = $path ? '/' . ltrim($path, '/') : '';

        return (file_exists(__DIR__ . "/Resources{$path}")) ? __DIR__ . "/Resources{$path}" : '';
    }

    /**
     * @inheritDoc
     */
    public function resourcesUrl(string $path = ''): string
    {
        $cinfo = class_info($this);
        $path = $path ? '/' . ltrim($path, '/') : '';

        return (file_exists($cinfo->getDirname() . "/Resources{$path}")) ? $cinfo->getUrl() . "/Resources{$path}" : '';
    }

    /**
     * @inheritDoc
     */
    public function stack(string $screen, string $context, array $metaboxes): MetaboxManagerContract
    {
        foreach ($metaboxes as $name => $item) {
            $metabox = $this->add($name, $item);
            $metabox->setScreen($screen)->setContext($context);
        }
        return $this;
    }
}