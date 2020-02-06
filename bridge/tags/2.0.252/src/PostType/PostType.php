<?php declare(strict_types=1);

namespace tiFy\PostType;

use Psr\Container\ContainerInterface as Container;
use tiFy\Contracts\PostType\{
    PostType as PostTypeContract,
    PostTypeFactory as PostTypeFactoryContract,
    PostTypePostMeta as PostTypePostMetaContract,
    PostTypeStatus as PostTypeStatusContract};

class PostType implements PostTypeContract
{
    /**
     * Instance du conteneur d'injection de dépendance.
     * @var Container|null
     */
    protected $container;

    /**
     * Liste des instances de type de post déclarée.
     * @var PostTypeFactoryContract[]|array
     */
    protected $items = [];

    /**
     * CONSTRUCTEUR.
     *
     * @param Container|null $container Instance du conteneur d'injection de dépendances.
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
    public function get(string $name): ?PostTypeFactoryContract
    {
        return $this->items[$name] ?? null;
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
    public function meta(): PostTypePostMetaContract
    {
        return ($c = $this->getContainer()) ? $c->get('post-type.meta') : new PostTypePostMeta();
    }

    /**
     * @inheritDoc
     */
    public function register(string $name, array $args = []): PostTypeFactoryContract
    {
        return $this->items[$name] = (new PostTypeFactory($name, $args))->setManager($this)->prepare();
    }

    /**
     * @inheritDoc
     */
    public function status(string $name, array $args = []): PostTypeStatusContract
    {
        return (!$args && ($exists = PostTypeStatus::instance($name))) ? $exists : PostTypeStatus::create($name, $args);
    }
}