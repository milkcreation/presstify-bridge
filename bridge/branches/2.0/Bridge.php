<?php declare(strict_types=1);

namespace tiFy\Plugins\Bridge;

use App\App;
use tiFy\Container\Container;
use tiFy\Kernel\KernelServiceProvider;

/**
 * @desc Bridge PresstifyFramework.
 * @author Jordy Manner <jordy@milkcreation.fr>
 * @package tiFy\Plugins\Bridge
 * @version 2.0.250
 * @copyright Milkcreation
 */
class Bridge extends Container
{
    /**
     * Instance de la classe
     * @var self
     */
    protected static $instance;

    /**
     * Liste des fournisseurs de service.
     * @var string[]
     */
    protected $serviceProviders = [
        KernelServiceProvider::class
    ];

    /**
     * CONSTRUCTEUR.
     *
     * @return void
     */
    public function __construct()
    {
        if (defined('WP_INSTALLING') && (WP_INSTALLING === true)) {
            return;
        }

        if (self::instance()) {
            return;
        }

        self::$instance = $this;

        parent::__construct();

        add_action('plugins_loaded', function () {
            load_muplugin_textdomain('tify', '/presstify/languages/');
            do_action('tify_load_textdomain');
        });

        add_action('after_setup_theme', function () {
            if (class_exists(App::class)) {
                $this->share('app', new App($this));
            }
        }, 0);
    }

    /**
     * Récupération de l'instance courante.
     *
     * @return static|null
     */
    public static function instance(): ?Bridge
    {
        return self::$instance instanceof static ? self::$instance : null;
    }
}
