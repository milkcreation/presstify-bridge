<?php declare(strict_types=1);

namespace tiFy\Metabox;

use Illuminate\Support\Collection;
use tiFy\Contracts\Metabox\{MetaboxDriver, MetaboxManager, MetaboxScreen as MetaboxScreenContract};
use tiFy\Support\{ParamsBag, Proxy\Request, Proxy\Router};

class MetaboxScreen extends ParamsBag implements MetaboxScreenContract
{
    /**
     * Nom de qualification.
     * @var string
     */
    protected $name;

    /**
     * Instance du gestionnaire de metaboxes.
     * @var MetaboxManager|null
     */
    protected $manager;

    /**
     * Indicateur d'écran d'affichage courant.
     * @var boolean|null
     */
    protected $current;

    /**
     * @inheritDoc
     */
    public function isCurrent(): bool
    {
        if (is_null($this->current)) {
            if ($this->isCurrentRoute()) {
                $this->current = true;
            } elseif ($this->isCurrentRequest()) {
                $this->current = true;
            } else {
                $this->current = false;
            }
        }

        return $this->current;
    }

    /**
     * @inheritDoc
     */
    public function isCurrentRoute(): bool
    {
        return Router::hasCurrent() && (Router::currentRouteName() === $this->name);
    }

    /**
     * @inheritDoc
     */
    public function isCurrentRequest(): bool
    {
        return ltrim(rtrim(Request::getPathInfo(), '/'), '/') === ltrim(rtrim($this->name, '/'), '/');
    }

    /**
     * @inheritDoc
     */
    public function getMetaboxes(): array
    {
        return (new Collection($this->manager()->all()))->filter(function (MetaboxDriver $box) {
            return $box->screen() === $this;
        })->all();
    }

    /**
     * @inheritDoc
     */
    public function manager(): ?MetaboxManager
    {
        return $this->manager;
    }

    /**
     * @inheritDoc
     */
    public function setManager(MetaboxManager $manager): MetaboxScreenContract
    {
        $this->manager = $manager;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setName(string $name): MetaboxScreenContract
    {
        $this->name = $name;

        return $this;
    }
}