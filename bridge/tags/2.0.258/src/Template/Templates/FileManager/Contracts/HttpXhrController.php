<?php declare(strict_types=1);

namespace tiFy\Template\Templates\FileManager\Contracts;

use League\Route\Http\Exception\MethodNotAllowedException;
use tiFy\Contracts\Template\FactoryHttpXhrController;

interface HttpXhrController extends FactoryHttpXhrController
{
    /**
     * Répartiteur des requêtes HTTP de la méthode POST.
     *
     * @return mixed
     *
     * @throws MethodNotAllowedException
     */
    public function post();

    /**
     * Parcours d'un dossier.
     *
     * @param string $path Chemin relatif vers le dossier.
     *
     * @return array
     */
    public function browse(string $path): array;

    /**
     * Création de dossier.
     *
     * @param string $path Chemin relatif vers le dossier.
     *
     * @return array
     */
    public function create(string $path): array;

    /**
     * Suppression d'un élément (fichier ou dossier).
     *
     * @param string $path Chemin relatif vers l'élément.
     *
     * @return array
     */
    public function delete(string $path): array;

    /**
     * Récupération d'un élément (fichier ou dossier).
     *
     * @param string $path Chemin relatif vers l'élément.
     *
     * @return array
     */
    public function get(string $path): array;

    /**
     * Renommage d'un élément (fichier ou dossier).
     *
     * @param string $path Chemin relatif vers l'élément.
     *
     * @return array
     */
    public function rename(string $path): array;

    /**
     * Téléversement de fichiers.
     *
     * @param string $path Chemin relatif vers le dossier de dépôt.
     *
     * @return array
     */
    public function upload(string $path): array;
}