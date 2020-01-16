<?php declare(strict_types=1);

namespace tiFy\Contracts\Filesystem;

use League\Flysystem\FileNotFoundException;
use Psr\Http\Message\ServerRequestInterface;
use Symfony\Component\HttpFoundation\StreamedResponse;

interface StaticCacheManager extends StorageManager
{
    /**
     * Vérification d'existance d'un fichier en cache.
     *
     * @param string $path Chemin relatif du fichier dans le disque de cache.
     * @param array $params Image manipulation params.
     *
     * @return boolean
     */
    public function cacheFileExists(string $path, array $params): bool;

    /**
     * Suppression du cache.
     *
     * @param string $path Chemin vers le fichier en cache.
     *
     * @return void
     *
     * @throws FileNotFoundException
     */
    public function clearCache(string $path = ''): void;

    /**
     * Récupération de la réponse HTTP.
     *
     * @param string $path Chemin relatif vers un fichier source.
     * @param ServerRequestInterface $psrRequest Requête Psr.
     *
     * @return StreamedResponse
     */
    public function getResponse(string $path, ServerRequestInterface $psrRequest);

    /**
     * Récupération du gestionnaire des ressources en cache.
     *
     * @return Filesystem|null
     */
    public function getCache(): ?Filesystem;

    /**
     * Récupération du chemin relatif vers un fichier en cache.
     *
     * @param string $path Chemin relatif du fichier dans le disque cache.
     * @param array $params Liste des paramètres passés en argument à la requête de récupération.
     *
     * @return string|null
     */
    public function getCachePath(string $path, array $params): ?string;

    /**
     * Récupération du gestionnaire des ressources originales.
     *
     * @return Filesystem|null
     */
    public function getSource(): ?Filesystem;

    /**
     * Récupération du chemin relatif vers un fichier source.
     *
     * @param string $path Chemin relatif du fichier dans le disque source.
     *
     * @return string|null
     */
    public function getSourcePath(string $path): ?string;

    /**
     * Vérifie si le système de cache est prêt.
     *
     * @return boolean
     */
    public function ready(): bool;

    /**
     * Définition du gestionnaire des ressources en cache.
     *
     * @param Filesystem $cache
     *
     * @return static
     */
    public function setCache(Filesystem $cache): StaticCacheManager;

    /**
     * Définition du gestionnaire des ressources originales.
     *
     * @param Filesystem $source
     *
     * @return static
     */
    public function setSource(Filesystem $source): StaticCacheManager;
}