<?php declare(strict_types=1);

namespace tiFy\Contracts\Template;

use Psr\Http\Message\ServerRequestInterface;
use tiFy\Contracts\Support\ParamsBag;
use tiFy\Contracts\View\ViewEngine;

interface TemplateFactory extends ParamsBag
{
    /**
     * Résolution de sortie de la classe en tant que chaîne de caractère.
     *
     * @return string
     */
    public function __toString(): string;

    /**
     * Instance du controleur de gestion des assets.
     *
     * @return FactoryAssets
     */
    public function assets(): FactoryAssets;

    /**
     * Url de routage.
     *
     * @param boolean $absolute Activation de la récupération de l'url absolue.
     *
     * @return string
     */
    public function baseUrl(bool $absolute = false): string;

    /**
     * Initialisation du controleur.
     *
     * @return void
     */
    public function boot(): void;

    /**
     * Vérification d'existance d'un service fourni.
     *
     * @param string $alias Alias de qualification du service.
     *
     * @return mixed.
     */
    public function bound(string $alias);

    /**
     * Instance du controleur de cache.
     *
     * @return FactoryCache
     */
    public function cache(): FactoryCache;

    /**
     * Récupération de l'instance du controleur de base de données
     *
     * @return FactoryDb|null
     */
    public function db(): ?FactoryDb;

    /**
     * Affichage du rendu.
     *
     * @return void
     */
    public function display();

    /**
     * Récupération de la liste des fournisseurs de services.
     *
     * @return string[]
     */
    public function getServiceProviders();

    /**
     * Récupération de l'instance du controleur des requêtes HTTP des ressources en cache.
     *
     * @param string $path Chemin vers la ressource en cache.
     * @param ServerRequestInterface $psrRequest Instance de la requête Psr.
     *
     * @return mixed
     */
    public function httpCacheController(string $path, ServerRequestInterface $psrRequest);

    /**
     * Récupération du controleur de requête HTTP.
     *
     * @param ServerRequestInterface $psrRequest Instance de la requête Psr.
     *
     * @return mixed
     */
    public function httpController(ServerRequestInterface $psrRequest);

    /**
     * Récupération du controleur de requêtes XmlHttpRequest (via ajax).
     *
     * @param ServerRequestInterface $psrRequest Instance de la requête Psr.
     *
     * @return mixed
     */
    public function httpXhrController(ServerRequestInterface $psrRequest);

    /**
     * Récupération de l'instance du controleur des intitulés ou récupération d'un intitulé.
     *
     * @param string|array|null $key Clé d'indexe de l'intitulé.
     * @param string $default Valeur de retour par défaut.
     *
     * @return FactoryLabels|string
     */
    public function label($key = null, string $default = '');

    /**
     * Récupération du nom de qualification du controleur.
     *
     * @return string
     */
    public function name(): string;

    /**
     * Récupération de l'instance du controleur de message de notification.
     *
     * @return FactoryNotices
     */
    public function notices(): FactoryNotices;

    /**
     * Récupération de l'instance du controleur de paramètre ou récupération d'un paramètre.
     *
     * @param string|array|null $key Clé d'indice du paramètres. Syntaxe à point permise.
     * @param mixed $default Valeur de retour par défaut.
     *
     * @return FactoryParams|mixed
     */
    public function param($key = null, $default = null);

    /**
     * Préparation des éléments d'affichage.
     *
     * @return static
     */
    public function prepare(): TemplateFactory;

    /**
     * Procéde aux actions de traitement requises.
     *
     * @return static
     */
    public function proceed(): TemplateFactory;

    /**
     * Récupération de l'instance du constructeur de requête.
     *
     * @return FactoryBuilder
     */
    public function builder(): FactoryBuilder;

    /**
     * Récupération d'un fournisseur de service.
     *
     * @return object|callable|null
     */
    public function provider($alias);

    /**
     * Récupération de l'instance du controleur de requête Http.
     *
     * @return FactoryRequest
     */
    public function request(): FactoryRequest;

    /**
     * Affichage.
     *
     * @return string
     */
    public function render();

    /**
     * Récupération d'une instance de service fourni.
     *
     * @param string $alias Nom de qualification du service.
     * @param array $args Liste des variables passées en argument
     *
     * @return mixed.
     */
    public function resolve(string $alias, array $args = []);

    /**
     * Définition de l'instance du template.
     *
     * @param string $name Nom de qualification.
     * @param TemplateManager $manager Instance du gestionnaire de templates.
     *
     * @return static
     */
    public function setInstance(string $name, TemplateManager $manager): TemplateFactory;

    /**
     * Récupération de l'identifiant de qualification compatible à l'utilisation dans une url.
     *
     * @return string
     */
    public function slug(): string;

    /**
     * Instance du controleur de gestion des urls.
     *
     * @return FactoryUrl
     */
    public function url(): FactoryUrl;

    /**
     * Récupération de l'instance du controleur de gabarit d'affichage ou du gabarit qualifié.
     *
     * @param string|null $view Nom de qualification du gabarit d'affichage.
     * @param array $data Liste des variables passées en argument au gabarit.
     *
     * @return FactoryViewer|ViewEngine
     */
    public function viewer(?string $view = null, array $data = []);
}