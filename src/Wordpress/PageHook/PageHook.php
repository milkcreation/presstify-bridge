<?php declare(strict_types=1);

namespace tiFy\Wordpress\PageHook;

use Illuminate\Support\Collection;
use tiFy\Wordpress\Contracts\{PageHook as PageHookContract, PageHookItem as PageHookItemContract};
use tiFy\Support\Proxy\Metabox;
use WP_Screen;

class PageHook implements PageHookContract
{
    /**
     * Instance de l'accroche courante.
     * @var PageHookItemContract|null
     */
    protected $current;

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

        add_action('init', function () {
            add_rewrite_tag('%hookname%', '([^&]+)');
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
    public function current(): ?PageHookItemContract
    {
        if (is_null($this->current)) {
            $this->current = did_action('parse_query') ? false : null;

            foreach($this->items as $item) {
                if ($item->is()) {
                    $this->current = $item;
                    break;
                }
            }
        }

        return $this->current ? : null;
    }

    /**
     * @inheritDoc
     */
    public function get($name): ?PageHookItemContract
    {
        $hook = $this->items[$name] ?? null;

        if ($hook instanceof PageHookItemContract) {
            $hook = $hook->exists() ? $hook : null;
        }

        return $hook ? : new PageHookFaker($name);
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