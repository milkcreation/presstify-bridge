<?php declare(strict_types=1);

namespace tiFy\Wordpress\Http;

use tiFy\Contracts\Http\Request;
use tiFy\Wordpress\Routing\WpScreen;
use WP_Screen;

class Http
{
    /**
     * Instance de la requête Http Globale.
     * @var Request
     */
    protected $request;

    /**
     * CONSTRUCTEUR.
     *
     * @return void
     */
    public function __construct(Request $request)
    {
        $this->request = $request;

        add_action('current_screen', function (WP_Screen $wp_screen) {
            $this->request->attributes->set('wp_screen', new WpScreen($wp_screen));
        }, 0);
    }
}