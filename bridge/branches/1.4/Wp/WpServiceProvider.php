<?php

namespace tiFy\Wp;

use Psr\Http\Message\ServerRequestInterface;
use tiFy\Container\ServiceProvider;

class WpServiceProvider extends ServiceProvider
{
    /**
     * Liste des services fournis.
     * @var array
     */
    protected $provides = [
        'wp'
    ];

    /**
     * @inheritdoc
     */
    public function boot()
    {
        add_action('after_setup_tify', function () {

        });

        add_action('wp', function () {
            try {
                $response = router()->dispatch($this->getContainer()->get(ServerRequestInterface::class));
                router()->emit($response);

                if ($response->getHeaders() || $response->getBody()->getSize()) :
                    exit;
                endif;
            } catch (\Exception $e) {

            }
        }, 0);
    }

    /**
     * Déclaration du controleur de gestion de Wordpress.
     *
     * @return void
     */
    public function registerManager()
    {
        $this->getContainer()->share('wp', WpManager::class);
    }
}