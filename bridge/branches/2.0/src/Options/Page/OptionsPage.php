<?php declare(strict_types=1);

namespace tiFy\Options\Page;

use tiFy\Contracts\Options\OptionsPage as OptionsPageContract;
use tiFy\Contracts\View\Engine as ViewEngine;
use tiFy\Support\{ParamsBag, Proxy\Metabox, Proxy\View};
use WP_Admin_Bar;
use WP_Screen;

class OptionsPage extends ParamsBag implements OptionsPageContract
{
    /**
     * Liste des éléments.
     * @var array
     */
    protected $items = [];

    /**
     * Nom de qualification.
     * @var string
     */
    protected $name = '';

    /**
     * Instance du moteur de gabarits d'affichage.
     * @return ViewEngine
     */
    protected $viewer;

    /**
     * CONSTRUCTEUR.
     *
     * @param string $name Nom de qualification.
     * @param array $attrs Liste des attributs de configuration.
     *
     * @return void
     */
    public function __construct(string $name, array $attrs = [])
    {
        $this->name = $name;

        $this->set($attrs)->parse();

        add_action('admin_menu', function () {
            if ($attrs = $this->get('admin_menu', [])) {
                if ($attrs['parent_slug']) {
                    add_submenu_page(
                        $attrs['parent_slug'],
                        $attrs['page_title'],
                        $attrs['menu_title'],
                        $attrs['capability'],
                        $attrs['menu_slug'],
                        $attrs['function']
                    );
                } else {
                    add_menu_page(
                        $attrs['page_title'],
                        $attrs['page_title'],
                        $attrs['capability'],
                        $attrs['menu_slug'],
                        $attrs['function'],
                        $attrs['icon_url'],
                        $attrs['position']
                    );
                }
            }
        });

        add_action('admin_bar_menu', function (WP_Admin_Bar &$wp_admin_bar) {
            if ($this->items && !is_admin() && ($admin_bar = $this->get('admin_bar', []))) {
                $wp_admin_bar->add_node($admin_bar);
            }
        }, 50);
    }

    /**
     * @inheritDoc
     */
    public function __toString(): string
    {
        return (string)$this->render();
    }

    /**
     * @inheritDoc
     */
    public function add(string $name, array $attrs = []): OptionsPageContract
    {
        $this->items[$name] = $attrs;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function boot(): void { }

    /**
     * @inheritDoc
     */
    public function defaults(): array
    {
        return [
            'admin_bar'             => [],
            'admin_menu'            => [],
            'cap'                   => 'manage_options',
            'hookname'              => 'settings_page_' . $this->getName(),
            'items'                 => [],
            'menu_title'            => __('Options du site', 'tify'),
            'page_title'            => __('Réglages', 'tify'),
        ];
    }

    /**
     * @inheritDoc
     */
    public function getHookname(): string
    {
        return $this->get('hookname');
    }

    /**
     * @inheritDoc
     */
    public function getItems(): array
    {
        return $this->items;
    }

    /**
     * @inheritDoc
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @inheritDoc
     */
    public function load(WP_Screen $wp_screen): void { }

    /**
     * @inheritDoc
     */
    public function parse(): OptionsPageContract
    {
        parent::parse();

        $this->set('name', $this->getName());

        return $this->parseAdminMenu()
            ->parseAdminBar()
            ->parseItems();
    }

    /**
     * @inheritDoc
     */
    public function parseAdminMenu(): OptionsPageContract
    {
        $this->set('admin_menu', array_merge([
            'parent_slug' => 'options-general.php',
            'page_title'  => $this->get('page_title'),
            'menu_title'  => $this->get('menu_title'),
            'capability'  => $this->get('cap'),
            'menu_slug'   => $this->getName(),
            'function'    => function () {
                echo $this->render();
            },
            'icon_url'    => '',
            'position'    => null,
        ], $this->get('admin_menu', [])));

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function parseAdminBar(): OptionsPageContract
    {
        $this->set('admin_bar', array_merge([
            'id'     => $this->getName(),
            'title'  => $this->get('menu_title'),
            'parent' => 'site-name',
            'href'   => admin_url('/options-general.php?page=' . $this->get('admin_menu.menu_slug')),
            'group'  => false,
            'meta'   => [],
        ], $this->get('admin_bar', [])));

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function parseItems(): OptionsPageContract
    {
        foreach ($this->get('items', []) as $name => $attrs) {
            $this->items[$name] = $attrs;

            Metabox::add($name, $attrs)
                ->setScreen("{$this->getName()}@options")
                ->setContext('tab');
        }

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function render(): string
    {
        return $this->viewer('options-page', $this->all());
    }

    /**
     * @inheritDoc
     */
    public function viewer(?string $view = null, array $data = [])
    {
        if (!$this->viewer) {
            $this->viewer = View::getPlatesEngine([
                'directory' => class_info($this)->getDirname() . '/views',
            ]);
        }

        if (func_num_args() === 0) {
            return $this->viewer;
        }

        return $this->viewer->render($view, $data);
    }
}