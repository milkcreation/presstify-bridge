<?php declare(strict_types=1);

namespace tiFy\Wordpress\Option;

use tiFy\Wordpress\Contracts\Option\{Option as OptionContract, OptionPage as OptionPageContract};
use tiFy\Contracts\View\Engine as ViewEngine;
use tiFy\Support\{ParamsBag, Proxy\View};
use WP_Admin_Bar;

class OptionPage extends ParamsBag implements OptionPageContract
{
    /**
     * Nom de qualification.
     * @var string
     */
    protected $name = '';

    /**
     * Instance du gestionnaire d'options.
     * @var OptionContract
     */
    protected $manager;

    /**
     * Instance du moteur de gabarits d'affichage.
     * @return ViewEngine
     */
    protected $view;

    /**
     * CONSTRUCTEUR.
     *
     * @return void
     */
    public function __construct()
    {
        add_action('admin_menu', function () {
            if ($attrs = $this->get('admin_menu', [])) {
                if ($attrs['parent_slug']) {
                    $hookname = add_submenu_page(
                        $attrs['parent_slug'],
                        $attrs['page_title'],
                        $attrs['menu_title'],
                        $attrs['capability'],
                        $attrs['menu_slug'],
                        $attrs['function']
                    );
                } else {
                    $hookname = add_menu_page(
                        $attrs['page_title'],
                        $attrs['page_title'],
                        $attrs['capability'],
                        $attrs['menu_slug'],
                        $attrs['function'],
                        $attrs['icon_url'],
                        $attrs['position']
                    );
                }
                $this->set(compact('hookname'));
            }
        });

        add_action('admin_bar_menu', function (WP_Admin_Bar &$wp_admin_bar) {
            if (!is_admin() && ($attrs = $this->get('admin_bar', []))) {
                $attrs['href'] = $attrs['href'] ??
                    admin_url('/options-general.php?page=' . $this->get('admin_menu.menu_slug', $this->getName()));

                $wp_admin_bar->add_node($attrs);
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
    public function boot(): void { }

    /**
     * @inheritDoc
     */
    public function defaults(): array
    {
        return [
            'admin_bar'  => false,
            'admin_menu' => true,
            'cap'        => 'manage_options',
            'hookname'   => null,
            'title'      => __('Réglage des options', 'tify'),
            'page_title' => null,
            'view'       => [],
        ];
    }

    /**
     * @inheritDoc
     */
    public function getHookname(): string
    {
        return $this->get('hookname', 'settings_page_' . $this->getName());
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
    public function isSettingsPage(): bool
    {
        return !!preg_match('/^settings_page_/', $this->getHookname());
    }

    /**
     * @inheritDoc
     */
    public function parse(): OptionPageContract
    {
        parent::parse();

        $this->set('name', $this->getName());

        if (is_null($this->get('page_title'))) {
            $this->set('page_title', $this->get('title'));
        }

        if ($admin_menu = $this->get('admin_menu')) {
            $this->set([
                'admin_menu' => array_merge([
                    'parent_slug' => 'options-general.php',
                    'page_title'  => $this->get('title'),
                    'menu_title'  => $this->get('title'),
                    'capability'  => $this->get('cap'),
                    'menu_slug'   => $this->getName(),
                    'function'    => function () {
                        echo $this->render();
                    },
                    'icon_url'    => '',
                    'position'    => null,
                ], is_array($admin_menu) ? $admin_menu : []),
            ]);
        }

        if ($admin_bar = $this->get('admin_bar')) {
            $this->set([
                'admin_bar' => array_merge([
                    'id'     => $this->getName(),
                    'title'  => $this->get('title'),
                    'parent' => 'site-name',
                    'group'  => false,
                    'meta'   => [],
                ], is_array($admin_bar) ? $admin_bar : []),
            ]);
        }

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function registerSettings(array $settings): OptionPageContract
    {
        foreach($settings as $k => $setting) {
            if (is_numeric($k)) {
                $name = (string) $setting;
                $args = [];
            } else {
                $name = $k;
                $args = $setting;
            }

            register_setting($this->getName(), $name, $args);
        }

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function render(): string
    {
        return $this->view('index', $this->all());
    }

    /**
     * @inheritDoc
     */
    public function setManager(OptionContract $manager): OptionPageContract
    {
        $this->manager = $manager;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setName(string $name): OptionPageContract
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function view(?string $name = null, array $data = [])
    {
        if (!$this->view) {
            $this->view = View::getPlatesEngine(array_merge([
                'directory'   => class_info($this->manager)->getDirname() . '/Resources/views/',
                'factory'     => OptionPageView::class,
                'option_page' => $this,
            ], config('options.view', []), $this->get('view', [])));
        }

        if (func_num_args() === 0) {
            return $this->view;
        }

        return $this->view->render($name, $data);
    }
}