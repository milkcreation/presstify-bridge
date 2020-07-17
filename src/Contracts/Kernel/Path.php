<?php declare(strict_types=1);

namespace tiFy\Contracts\Kernel;

use tiFy\Contracts\Filesystem\{Filesystem, LocalFilesystem};
use tiFy\Contracts\Filesystem\StorageManager;

interface Path extends StorageManager
{
    /**
     * {@inheritDoc}
     *
     * @return LocalFilesystem|null
     */
    public function disk(?string $name = null): ?Filesystem;

    /**
     * {@inheritDoc}
     *
     * @return LocalFilesystem|null
     */
    public function getDefault(): ?Filesystem;

    /**
     * {@inheritDoc}
     *
     * @return LocalFilesystem|null
     */
    public function getFilesystem($prefix): ?LocalFilesystem;

    /**
     * Récupération de l'instance du gestionnaire de dossier racine.
     *
     * @return LocalFilesystem
     */
    public function diskBase(): LocalFilesystem;

    /**
     * Récupération de l'instance du gestionnaire du dossier de stockage des fichiers de cache.
     *
     * @return LocalFilesystem
     */
    public function diskCache(): LocalFilesystem;

    /**
     * Récupération de l'instance du gestionnaire du dossier de stockage des fichiers de configuration.
     *
     * @return LocalFilesystem
     */
    public function diskConfig(): LocalFilesystem;

    /**
     * Récupération de l'instance du gestionnaire du dossier de stockage des fichiers de journalisation.
     *
     * @return LocalFilesystem
     */
    public function diskLog(): LocalFilesystem;

    /**
     * Récupération du chemin de gestionnaire de fichiers par rapport à la racine.
     *
     * @param LocalFilesystem $disk
     * @param string $path Chemin absolue vers un dossier ou un fichier du gestionnaire.
     * @param boolean $absolute Activation de la récupération du chemin en absolu.
     *
     * @return string
     */
    public function diskPathFromBase(LocalFilesystem $disk, string $path = '', bool $absolute = true): ?string;

    /**
     * Récupération de l'instance du gestionnaire du dossier publique.
     *
     * @return LocalFilesystem
     */
    public function diskPublic(): LocalFilesystem;

    /**
     * Récupération de l'instance du gestionnaire du dossier de stockage.
     *
     * @return LocalFilesystem
     */
    public function diskStorage(): LocalFilesystem;

    /**
     * Récupération de l'instance du gestionnaire du dossier du theme.
     *
     * @return LocalFilesystem
     */
    public function diskTheme(): LocalFilesystem;

    /**
     * Récupération de l'instance du gestionnaire du dossier du framework PresstiFy.
     *
     * @return LocalFilesystem
     */
    public function diskTiFy(): LocalFilesystem;

    /**
     * Récupération du chemin vers un dossier ou un fichier du répertoire de base (racine).
     *
     * @param string $path Chemin relatif vers un fichier ou un dossier du repertoire.
     * @param bool $rel Activation de la sortie du chemin au format relatif.
     *
     * @return string
     */
    public function getBasePath(string $path = '', bool $rel = false): string;

    /**
     * Récupération du chemin vers un dossier ou un fichier du répertoire de cache.
     *
     * @param string $path Chemin relatif vers un fichier ou un dossier du répertoire.
     * @param bool $rel Activation de la sortie du chemin au format relatif.
     *
     * @return string
     */
    public function getCachePath(string $path = '', bool $rel = false): string;

    /**
     * Récupération du chemin vers un dossier ou un fichier du répertoire de configuration.
     *
     * @param string $path Chemin relatif vers un fichier ou un dossier du répertoire.
     * @param bool $rel Activation de la sortie du chemin au format relatif.
     *
     * @return string
     */
    public function getConfigPath(string $path = '', bool $rel = false): string;

    /**
     * Récupération du chemin vers un dossier ou un fichier du répertoire de journalisation.
     *
     * @param string $path Chemin relatif vers un fichier ou un dossier du répertoire.
     * @param bool $rel Activation de la sortie du chemin au format relatif.
     *
     * @return string
     */
    public function getLogPath(string $path = '', bool $rel = false): string;

    /**
     * Récupération du chemin vers un dossier ou un fichier du répertoire publique.
     *
     * @param string $path Chemin relatif vers un fichier ou un dossier du répertoire.
     * @param bool $rel Activation de la sortie du chemin en relatif.
     *
     * @return string
     */
    public function getPublicPath(string $path = '', bool $rel = false): string;

    /**
     * Récupération du chemin vers un dossier ou un fichier du répertoire de stockage.
     *
     * @param string $path Chemin relatif vers un fichier ou un dossier du répertoire.
     * @param bool $rel Activation de la sortie du chemin en relatif.
     *
     * @return string
     */
    public function getStoragePath(string $path = '', bool $rel = false): string;

    /**
     * Récupération du chemin vers un dossier ou un fichier du répertoire du thème.
     *
     * @param string $path Chemin relatif vers un fichier ou un dossier du répertoire.
     * @param bool $rel Activation de la sortie du chemin en relatif.
     *
     * @return string
     */
    public function getThemePath(string $path = '', bool $rel = false): string;

    /**
     * Récupération du chemin vers un dossier ou un fichier du répertoire du framework presstiFy.
     *
     * @param string $path Chemin relatif vers un fichier ou un dossier du repertoire.
     * @param bool $rel Activation de la sortie du chemin en relatif.
     *
     * @return string
     */
    public function getTiFyPath(string $path = '', bool $rel = false): string;

    /**
     * Vérification du type d'arborescence du projet.
     * @internal Vraie si les répertoires de Wordpress sont à la racine du projet.
     *
     * @return boolean
     */
    public function isWp(): bool;

    /**
     * Montage d'un disque local.
     *
     * @param string $name Nom de qualification.
     * @param string $root Chemin absolu vers la racine du disque.
     * @param array $config Configuration.
     *
     * @return LocalFilesystem
     */
    public function mount(string $name, string $root, array $config = []): LocalFilesystem;

    /**
     * Normalisation d'un chemin.
     *
     * @param string $path
     *
     * @return string
     */
    public function normalize(string $path): string;

    /**
     * Récupération du chemin par rapport à la racine.
     *
     * @param string $pathname Chemin absolue vers un dossier ou un fichier.
     *
     * @return string
     */
    public function relPathFromBase(string $pathname): ?string;
}