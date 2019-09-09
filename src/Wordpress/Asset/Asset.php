<?php declare(strict_types=1);

namespace tiFy\Wordpress\Asset;

use tiFy\Contracts\Asset\Asset as AssetManager;

class Asset
{
    /**
     * Instance du gestionnaire d'assets.
     * @var AssetManager
     */
    protected $manager;

    public function __construct(AssetManager $manager)
    {
        $this->manager = $manager;

        $ajax_url = admin_url('admin-ajax.php', 'relative');
        $this->manager->setInlineJs("let tify_ajaxurl='{$ajax_url}'");
        $this->manager->setDataJs('ajax_url', $ajax_url);
        $this->manager->setDataJs('base_url', $this->manager->getContainer()->get('request')->getBaseUrl(), false);
        $this->manager->setDataJs('rewrite_base', $this->manager->getContainer()->get('url')->rewriteBase(), false);

        add_action('admin_head', function () {
            echo $this->manager->header();
        }, 5);

        add_action('admin_footer', function () {
            echo $this->manager->footer();
        }, 5);

        add_action('init', function () {
            $lib = require_once(__DIR__ . '/Resources/config/third-party.php');
            foreach ($lib['css'] as $handle => $attrs) {
                wp_register_style($handle, $attrs[0], $attrs[1], $attrs[2], $attrs[3]);
            }
            foreach ($lib['js'] as $handle => $attrs) {
                wp_register_script($handle, $attrs[0], $attrs[1], $attrs[2], $attrs[3]);
            }
        });

        add_action('wp_head', function () {
            echo $this->manager->header();
        }, 5);

        add_action('wp_footer', function () {
            echo $this->manager->footer();
        }, 5);
    }
}