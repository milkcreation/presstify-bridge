<?php declare(strict_types=1);

namespace tiFy\Routing;

use Psr\Container\ContainerInterface as Container;
use tiFy\Contracts\Http\Response as ResponseContract;
use tiFy\Http\Response;
use tiFy\Support\ParamsBag;
use tiFy\Support\Proxy\View;

abstract class BaseController extends ParamsBag
{
    /**
     * Instance de conteneur d'injection de dépendances.
     * @var Container|null
     */
    protected $container;

    /**
     * CONSTRUCTEUR.
     *
     * @param Container|null $container Instance de conteneur d'injection de dépendances.
     *
     * @return void
     */
    public function __construct(?Container $container = null)
    {
        $this->container = $container;

        $this->boot();
    }

    /**
     * Initialisation du controleur.
     *
     * @return void
     */
    public function boot(): void { }


    /**
     * Récupération de l'affichage d'un gabarit.
     *
     * @param string $view Nom de qualification du gabarit.
     * @param array $data Liste des variables passées en argument.
     *
     * @return ResponseContract
     */
    public function viewer(string $view, array $data = []): ?ResponseContract
    {
        return new Response(View::render($view, $data));
    }
}