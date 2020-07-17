<?php declare(strict_types=1);

namespace tiFy\Partial\Driver\Breadcrumb;

use tiFy\Contracts\Partial\{Breadcrumb as BreadcrumbContract, BreadcrumbCollection as BreadcrumbCollectionContract};
use tiFy\Partial\PartialDriver;

class Breadcrumb extends PartialDriver implements BreadcrumbContract
{
    /**
     * Instance principale.
     * @var BreadcrumbContract|null
     */
    protected static $main;

    /**
     * Indicateur de d'activation d'affichage du fil d'ariane.
     * @var bool
     */
    private $enabled = true;

    /**
     * Instance du gestionnaire de collection d'éléments.
     * @var BreadcrumbCollectionContract
     */
    protected $collection;

    /**
     * @inheritDoc
     */
    public function add($item): ?int
    {
        if ($item = $this->parseItem($item)) {
            return $this->collection()->add(...$item);
        }

        return null;
    }

    /**
     * @inheritDoc
     */
    public function append($item): ?int
    {
        return $this->add($item);
    }

    /**
     * @inheritDoc
     */
    public function collection(): BreadcrumbCollectionContract
    {
        if (is_null($this->collection)) {
            $this->collection = new BreadcrumbCollection($this);
        }

        return $this->collection;
    }

    /**
     * {@inheritDoc}
     *
     * @return array {
     * @var array $attrs Attributs HTML du champ.
     * @var string $after Contenu placé après le champ.
     * @var string $before Contenu placé avant le champ.
     * @var array $viewer Liste des attributs de configuration du pilote d'affichage.
     * @var string[]|array[]|object[]|callable[] $items Liste des élements du fil d'ariane.
     * }
     */
    public function defaults(): array
    {
        return [
            'attrs'  => [],
            'after'  => '',
            'before' => '',
            'viewer' => [],
            'items'  => [],
        ];
    }

    /**
     * @inheritDoc
     */
    public function disable(): BreadcrumbContract
    {
        $this->enabled = false;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function enable(): BreadcrumbContract
    {
        $this->enabled = true;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function flush(): BreadcrumbContract
    {
        $this->collection()->clear();

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function main(): BreadcrumbContract
    {
        if (is_null(self::$main)) {
            self::$main = $this->manager()->get('breadcrumb');
        }

        return self::$main;
    }

    /**
     * @inheritDoc
     */
    public function isEnabled(): bool
    {
        return !!$this->enabled;
    }

    /**
     * @inheritDoc
     */
    public function insert(int $position, $item): ?int
    {
        if ($item = $this->parseItem($item)) {
            $item[1] = $position;

            return $this->collection()->add(...$item);
        }

        return null;
    }

    /**
     * @inheritDoc
     */
    public function move(int $from, int $to): ?int
    {
        return $this->collection()->move($from, $to);
    }

    /**
     * @inheritDoc
     */
    public function parseItem($item): ?array
    {
        if (is_string($item)) {
            return [$item, null, []];
        } elseif (is_object($item)) {
            return [(string)$item, null, []];
        } elseif (is_array($item)) {
            return [
                $this->collection()->getRender(
                    $item['content'] ?? '', $item['url'] ?? null, $item['attrs'] ?? []
                ),
                $item['position'] ?? null,
                $item['wrapper'] ?? [],
            ];
        }

        return null;
    }

    /**
     * @inheritDoc
     */
    public function prepend($item): ?int
    {
        return $this->insert(0, $item);
    }

    /**
     * @inheritDoc
     */
    public function remove(int $position): BreadcrumbContract
    {
        $this->collection()->clear($position);

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function render(): string
    {
        if ($this->get('main')) {
            return $this->main()->render();
        } else {
            $this->set('parts', $this->isEnabled() ? $this->collection()->fetch() : []);

            return parent::render();
        }
    }

    /**
     * @inheritDoc
     */
    public function replace(int $position, $item): ?int
    {
        if ($item = $this->parseItem($item)) {
            $item[1] = $position;

            return $this->collection()->clear($position)->add(...$item);
        }

        return null;
    }
}