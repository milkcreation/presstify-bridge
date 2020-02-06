<?php declare(strict_types=1);

namespace tiFy\Partial\Driver\Breadcrumb;

use Illuminate\Support\Collection;
use tiFy\Contracts\Partial\{Breadcrumb, BreadcrumbCollection as BreadcrumbCollectionContract};
use tiFy\Support\Proxy\Partial;

class BreadcrumbCollection implements BreadcrumbCollectionContract
{
    /**
     * Liste des éléments déclarés.
     * @var array
     */
    protected $items = [];

    /**
     * Instance du pilote de fil d'ariane.
     * @var Breadcrumb
     */
    protected $manager;

    /**
     * Liste des portions du fil d'ariane.
     * @var array
     */
    protected $parts = [];

    /**
     * CONSTRUCTEUR.
     *
     * @param Breadcrumb $manager Instance du pilote de fil d'ariane.
     *
     * @return void
     */
    public function __construct(Breadcrumb $manager)
    {
        $this->manager = $manager;
    }

    /**
     * @inheritDoc
     */
    public function add(string $render, ?int $position = null, array $wrapper = []): int
    {
        if (is_null($position)) {
            $position = count($this->items);
        } elseif (isset($this->items[$position])) {
            $items = [];
            (new Collection($this->items))
                ->sortKeysDesc()
                ->each(function (array $item, $key) use (&$items, $position) {
                    if ($key >= $position) {
                        $key++;
                    }
                    $items[$key] = $item;
                });
            $this->items = $items;
        }

        $this->items[$position] = compact('render', 'wrapper');

        return $position;
    }

    /**
     * @inheritDoc
     */
    public function all(): array
    {
        ksort($this->items, SORT_REGULAR);

        return $this->items;
    }

    /**
     * @inheritDoc
     */
    public function clear(?int $position = null): BreadcrumbCollectionContract
    {
        if (is_null($position)) {
            $this->items = [];
        } else {
            unset($this->items[$position]);
        }

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function fetch(): array
    {
        events()->trigger('partial.breadcrumb.fetch', [&$this]);

        $this->parts = (new Collection($this->items))->sortKeys()->map(function ($item) {
            return $this->parse($item);
        })->values()->all();

        return $this->parts;
    }

    /**
     * @inheritDoc
     */
    public function get(int $position): ?array
    {
        return $this->items[$position] ?? null;
    }

    /**
     * @inheritDoc
     */
    public function getRender(string $content, ?string $url = null, array $attrs = []): string
    {
        $attrs['class'] = sprintf($attrs['class'] ?? '%s', 'Breadcrumb-itemContent');

        if ($url) {
            $render = Partial::get('tag', [
                'attrs'   => array_merge($attrs, [
                    'href' => $url,
                ]),
                'content' => $content,
                'tag'     => 'a',
            ])->render();
        } else {
            $render = Partial::get('tag', [
                'attrs'   => $attrs,
                'content' => $content,
                'tag'     => 'span',
            ])->render();
        }

        return $render;
    }

    /**
     * @inheritDoc
     */
    public function getUrl($url, ?string $default = '#'): ?string
    {
        return is_string($url) ? $url : ($url ? $default : null);
    }

    /**
     * @inheritDoc
     */
    public function has(int $position): bool
    {
        return isset($this->items[$position]);
    }

    /**
     * @inheritDoc
     */
    public function manager(): Breadcrumb
    {
        return $this->manager;
    }

    /**
     * @inheritDoc
     */
    public function move(int $from, int $to): ?int
    {
        if ($item = $this->get($from)) {
            $this->clear($from);

            if ($exists = $this->get($to)) {
                $this->clear($to)->add($exists['render'], $from, $exists['wrapper']);
            }

            return $this->add($item['render'], $to, $item['wrapper']);
        }

        return null;
    }

    /**
     * @inheritDoc
     */
    public function parse(array $item): string
    {
        $wrapper = $item['wrapper'] ?? [];
        $wrapper['attrs']['class'] = sprintf($wrapper['attrs']['class'] ?? '%s', 'Breadcrumb-item');

        $tag = array_merge([
            'tag' => 'li'
        ], $wrapper, ['content' => $item['render'] ?? '']);

        return Partial::get('tag', $tag)->render();
    }
}