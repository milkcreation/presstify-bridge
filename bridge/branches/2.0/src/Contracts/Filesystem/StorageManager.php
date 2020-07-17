<?php declare(strict_types=1);

namespace tiFy\Contracts\Filesystem;

use InvalidArgumentException;
use League\Flysystem\{AdapterInterface, FilesystemInterface, FilesystemNotFoundException, MountManager};
use tiFy\Contracts\Container\Container;

/**
 * @mixin MountManager
 */
interface StorageManager extends FilesystemInterface
{
    /**
     * Récupération de l'instance d'un point de montage.
     *
     * @param string|null $name Nom de qualification du point de montage|Point de montage par défaut.
     *
     * @return Filesystem|null
     */
    public function disk(?string $name = null): ?Filesystem;

    /**
     * Récupération de l'instance du controleur d'injection de dépendances.
     *
     * @return Container|null
     */
    public function getContainer(): ?Container;

    /**
     * Récupération de l'instance du point de montage par défaut.
     *
     * @return Filesystem|null
     */
    public function getDefault(): ?Filesystem;

    /**
     * Récupération d'une instance gestionnaire de fichier.
     *
     * @param string $name Nom de qualification du point de montage.
     *
     * @return Filesystem
     *
     * @throws FilesystemNotFoundException
     */
    public function getFilesystem($name);

    /**
     * Création d'une instance de système de fichiers locaux de type image.
     *
     * @param  string $root Chemin absolu vers le répertoire du stockage de fichiers.
     * @param  array $config Liste des paramètres de configuration.
     *
     * @return ImgFilesystem
     */
    public function img(string $root, array $config = []): ImgFilesystem;

    /**
     * Création d'une instance de système de fichier locaux de type image.
     *
     * @param  string $root Chemin absolu vers le répertoire du stockage de fichiers.
     * @param  array $config Liste des paramètres de configuration.
     *
     * @return ImgAdapter
     */
    public function imgAdapter(string $root, array $config = []): AdapterInterface;

    /**
     * Création d'une instance de système de fichiers locaux.
     *
     * @param  string $root Chemin absolu vers le répertoire du stockage de fichiers.
     * @param  array $config Liste des paramètres de configuration.
     *
     * @return LocalFilesystem
     */
    public function local(string $root, array $config = []): LocalFilesystem;

    /**
     * Création d'une instance de système de fichier locaux.
     *
     * @param  string $root Chemin absolu vers le répertoire du stockage de fichiers.
     * @param  array $config Liste des paramètres de configuration.
     *
     * @return LocalAdapter
     */
    public function localAdapter(string $root, array $config = []): AdapterInterface;

    /**
     * Montage d'une instance de gestionnaire de fichiers.
     *
     * @param string $name Nom de qualification du disque.
     * @param FilesystemInterface $filesystem Instance du gestionnaire de fichiers.
     *
     * @return $this
     *
     * @throws InvalidArgumentException
     */
    public function mountFilesystem($name, FilesystemInterface $filesystem);

    /**
     * Déclaration d'un disque.
     *
     * @param string $name Nom de qualification.
     * @param array|Filesystem $attrs Liste des attributs de configuration|Instance du gestionnaire de fichiers.
     *
     * @return Filesystem|null
     */
    public function register(string $name, $attrs): ?Filesystem;

    /**
     * Déclaration d'un disque image.
     *
     * @param string $name Nom de qualification.
     * @param array|ImgFilesystem $attrs Liste des attributs de configuration|Instance du gestionnaire de fichiers.
     *
     * @return ImgFilesystem|null
     */
    public function registerImg(string $name, $attrs): ?Filesystem;

    /**
     * Déclaration d'un disque local.
     *
     * @param string $name Nom de qualification.
     * @param array|LocalFilesystem $attrs Liste des attributs de configuration|Instance du gestionnaire de fichiers.
     *
     * @return LocalFilesystem|null
     */
    public function registerLocal(string $name, $attrs): ?Filesystem;

    /**
     * Définition d'un disque.
     *
     * @param string $name Nom de qualification.
     * @param Filesystem $filesystem Instance du gestionnaire de fichiers.
     *
     * @return $this
     */
    public function set(string $name, Filesystem $filesystem): StorageManager;

    /**
     * Définition du conteneur d'injection de dépendances.
     *
     * @param Container $container
     *
     * @return static
     */
    public function setContainer(Container $container): StorageManager;

    /**
     * Récupération de l'instance d'un point de montage du système de fichier locaux.
     *
     * @param string|null $name Nom de qualification du point de montage système|Point de montage système par défaut.
     *
     * @return LocalFilesystem|null
     */
    public function system(?string $name = null): ?LocalFilesystem;
}