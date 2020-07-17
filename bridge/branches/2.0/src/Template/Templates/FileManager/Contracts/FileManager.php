<?php declare(strict_types=1);

namespace tiFy\Template\Templates\FileManager\Contracts;

use League\Flysystem\AdapterInterface;
use tiFy\Contracts\Filesystem\Filesystem;
use tiFy\Contracts\Template\{FactoryAjax, TemplateFactory as TemplateFactoryContract};
use tiFy\Contracts\Template\TemplateFactory;

interface FileManager extends TemplateFactory
{
    /**
     * Récupération de l'instance du gestionnaire de fichiers en contexte.
     *
     * @return AdapterInterface
     */
    public function adapter(): AdapterInterface;

    /**
     * {@inheritDoc}
     *
     * @return Ajax|null
     */
    public function ajax(): ?FactoryAjax;

    /**
     * Récupération de l'instance du controleur de fil d'ariane.
     *
     * @return Breadcrumb
     */
    public function breadcrumb(): Breadcrumb;

    /**
     * Récupération de l'instance du gestionnaire de fichiers.
     *
     * @return Filesystem
     */
    public function filesystem(): Filesystem;

    /**
     * Récupération d'un instance de fichier.
     *
     * @param string|null $path Chemin relatif vers le fichier. Si null, utilise le chemin relatif courant.
     *
     * @return Fileinfo|null
     */
    public function getFile(?string $path = null): ?Fileinfo;

    /**
     * Récupération de la liste des fichiers associée à un chemin relatif.
     *
     * @param string|null $path Chemin relatif vers le répertoire. Si null, utilise le chemin relatif courant.
     * @param boolean $recursive Activation du traitement récursif. (non recommandé).
     *
     * @return FileCollection|FileInfo[]|null
     */
    public function getFiles(?string $path = null, bool $recursive = false): ?FileCollection;

    /**
     * Récupération d'un icône.
     *
     * @param $name
     * @param array Liste des arguments dynamiques.
     *
     * @return string|null
     */
    public function getIcon($name, ...$args): ?string;

    /**
     * Récupération du chemin relatif courant.
     *
     * @return string
     */
    public function getPath(): string;

    /**
     * Récupération d'un instance du gestionnaire d'icones.
     *
     * @return IconSet
     */
    public function icon(): IconSet;

    /**
     * {@inheritdoc}
     *
     * @return FileManager
     */
    public function prepare(): TemplateFactoryContract;

    /**
     * Récupération de l'aperçu d'un élément.
     * {@internal Recherche un gabarit correspondant parmis les gabarits suivants (par priorité):
     * - "preview-{{ext}}" où {{ext}} correspond à l'extension du fichier. ex preview-jpg.
     * - "preview-{{type}}" où {{type}} correspond au type du type de média du fichier. ex preview-image.
     * - Par défaut affiche l'icône représentative du fichier.
     * }
     * @param FileInfo $file Fichier à prévisualiser.
     *
     * @return string
     */
    public function preview(FileInfo $file): string;

    /**
     * Définition du chemin relatif courant.
     *
     * @param string $path
     *
     * @return FileManager
     */
    public function setPath(string $path): FileManager;

    /**
     * Instance de la barre latérale de contrôle.
     *
     * @return Sidebar
     */
    public function sidebar(): Sidebar;
}