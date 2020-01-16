<?php declare(strict_types=1);

namespace tiFy\Routing;

use Psr\Container\ContainerInterface as Container;
use tiFy\Contracts\{Http\Response as ResponseContract, Http\RedirectResponse, Routing\Redirector};
use tiFy\Http\Response;
use tiFy\Support\ParamsBag;
use tiFy\Support\Proxy\{View, Redirect};

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
     * Récupération de l'instance du gestionnaire de redirection|Redirection vers un chemin.
     *
     * @param string|null $path url absolue|relative de redirection.
     * @param int $status Statut de redirection.
     * @param array $headers Liste des entêtes complémentaires associées à la redirection.
     *
     * @return RedirectResponse|Redirector
     */
    public function redirect(?string $path = null, int $status = 302, array $headers = [])
    {
        if (is_null($path)) {
            return Redirect::getInstance();
        } else {
            return Redirect::to($path, $status, $headers);
        }
    }

    /**
     *
     */
    public function response($content = '', int $status = 200, array $headers = []): ?ResponseContract
    {
        return new Response($content, $status, $headers);
    }

    /**
     * Redirection vers une route déclarée.
     *
     * @param string $name Nom de qualification de la route.
     * @param string $params Liste des paramètres de définition de l'url de la route.
     * @param int $status Statut de redirection.
     * @param array $headers Liste des entêtes complémentaires associées à la redirection.
     *
     * @return RedirectResponse
     */
    public function route(string $name, array $params= [], int $status = 302, array $headers = []): RedirectResponse
    {
        return Redirect::route($name, $params, $status, $headers);
    }

    /**
     * Récupération de l'affichage d'un gabarit.
     *
     * @param string $view Nom de qualification du gabarit.
     * @param array $data Liste des variables passées en argument.
     *
     * @return ResponseContract
     */
    public function view(string $view, array $data = []): ?ResponseContract
    {
        return $this->response(View::render($view, $data));
    }
}