<?php declare(strict_types=1);

namespace tiFy\Wordpress\PageHook;

use Illuminate\Support\Collection;
use tiFy\Wordpress\Contracts\{PageHook as PageHookContract, PageHookItem as PageHookItemContract};
use tiFy\Support\Proxy\Metabox;
use WP_Screen;

class PageHook implements PageHookContract
{
    /**
     * Liste des éléments déclarés.
     * @var PageHookItemContract[]
     */
    protected $items = [];

    /**
     * CONSTRUCTEUR.
     *
     * @return void
     */
    public function __construct()
    {
        $this->set(config('page-hook', []));

        add_action('admin_init', function () {
            if ($this->collect()->firstWhere('admin', '=', true)) {
                Metabox::add('PageHook-optionsNode', (new PageHookMetabox())->setPageHook($this))
                    ->setScreen('tify_options@options')
                    ->setContext('tab');

                $this->collect()->where('admin', true)->each(function (PageHookItemContract $item) {
                    register_setting('tify_options', $item->getOptionName());
                });
            }
        }, 999999);

        add_action('current_screen', function (WP_Screen $wp_screen) {
            if ($wp_screen->id === 'settings_page_tify_options') {
                flush_rewrite_rules();
            }
        });
    }

    /**
     * @inheritDoc
     */
    public function all(): array
    {
        return $this->items;
    }

    /**
     * @inheritDoc
     */
    public function collect(): Collection
    {
        return new Collection($this->items);
    }

    /**
     * @inheritDoc
     */
    public function get($name): ?PageHookItemContract
    {
        return $this->items[$name] ?? null;
    }

    /**
     * @inheritDoc
     */
    public function has(): bool
    {
        return !!$this->collect()->first(function (PageHookItemContract $hook) {
            return $hook->exists();
        });
    }

    /**
     * @inheritDoc
     */
    public function set($name, $attrs = null): PageHookContract
    {
        $keys = is_array($name) ? $name : [$name => $attrs];

        foreach ($keys as $k => $v) {
            $this->items[$k] = new PageHookItem($k, $v);
        }

        return $this;
    }
}