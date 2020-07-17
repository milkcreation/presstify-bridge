<?php declare(strict_types=1);

namespace tiFy\Contracts\Template;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Container\ContainerInterface;
use tiFy\Contracts\Container\Container;
use tiFy\Contracts\Support\Manager;

interface TemplateManager extends Manager
{
    /**
     * Récupération de l'instance du controleur des requêtes HTTP des ressources en cache.
     *
     * @param string $name Nom de qualification du gabarit d'affichage.
     * @param string $path Chemin vers la ressource en cache.
     * @param ServerRequestInterface $psrRequest Instance de la requête Psr.
     *
     * @return mixed
     */
    public function httpCacheController(string $name, string $path, ServerRequestInterface $psrRequest);

    /**
     * Récupération de l'instance du controleur des requêtes HTTP.
     *
     * @param string $name Nom de qualification du gabarit d'affichage.
     * @param ServerRequestInterface $psrRequest Instance de la requête Psr.
     *
     * @return mixed
     */
    public function httpController(string $name, ServerRequestInterface $psrRequest);

    /**
     * Récupération de l'instance du controleur des requêtes XmlHttpRequest (via ajax).
     *
     * @param string $name Nom de qualification du gabarit d'affichage.
     * @param ServerRequestInterface $psrRequest Instance de la requête Psr.
     *
     * @return mixed
     */
    public function httpXhrController(string $name, ServerRequestInterface $psrRequest);

    /**
     * Préparation des urls de routage.
     *
     * @return static
     */
    public function prepareRoutes(): TemplateManager;

    /**
     * {@inheritDoc}
     *
     * @return Container|null
     */
    public function getContainer(): ?ContainerInterface;

    /**
     * Récupération du chemin absolu vers le répertoire de stockage des ressources.
     *
     * @param string $path Chemin relatif vers une ressource du répertoire (fichier ou dossier).
     *
     * @return string
     */
    public function resourcesDir(?string $path = ''): ?string;

    /**
     * Récupération de l'url absolue vers le répertoire de stockage des ressources.
     *
     * @param string $path Chemin relatif vers une ressource du répertoire (fichier ou dossier).
     *
     * @return string
     */
    public function resourcesUrl(?string $path = ''): ?string;
}