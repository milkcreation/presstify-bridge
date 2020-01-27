<?php declare(strict_types=1);

namespace tiFy\Contracts\Filesystem;

use League\Flysystem\{AdapterInterface, FileNotFoundException, Filesystem as LeagueFilesystem, FilesystemInterface};
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * @mixin LeagueFilesystem
 */
interface Filesystem extends FilesystemInterface
{
    /**
     * Génération de la réponse de téléchargement d'un fichier.
     *
     * @param string $path Chemin relatif vers un fichier du disque.
     * @param string|null $name Nom de qualification du fichier.
     * @param array|null $headers Liste des entêtes de la réponse.
     *
     * @return StreamedResponse
     *
     * @throws FileNotFoundException
     */
    public function download(string $path, ?string $name = null, array $headers = []): StreamedResponse;

    /**
     * Récupération de l'adaptateur "réel", lorsque celui-ci est englobé dans un système de cache.
     *
     * @return AdapterInterface
     */
    public function getRealAdapter(): AdapterInterface;

    /**
     * Génération de la réponse d'un fichier.
     *
     * @param string $path Chemin relatif vers un fichier du disque.
     * @param string|null $name Nom de qualification du fichier.
     * @param array|null $headers Liste des entêtes de la réponse.
     * @param string|null $disposition inline (affichage)|attachment (téléchargement).
     *
     * @return StreamedResponse
     *
     * @throws FileNotFoundException
     */
    public function response(
        string $path,
        ?string $name = null,
        array $headers = [],
        $disposition = 'inline'
    ): StreamedResponse;
}