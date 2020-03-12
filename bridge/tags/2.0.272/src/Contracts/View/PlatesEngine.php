<?php declare(strict_types=1);

namespace tiFy\Contracts\View;

use League\Plates\Engine as BasePlatesEngine;
use League\Plates\Template\Folder;

/**
 * @mixin BasePlatesEngine
 */
interface PlatesEngine extends Engine
{
    /**
     * Récupération d'une instance de controleur d'un gabarit d'affichage.
     *
     * @param string $name Nom de qualification du gabarit.
     *
     * @return PlatesFactory
     */
    public function getFactory(string $name): PlatesFactory;

    /**
     * Récupération de l'instance d'un répertoire déclaré de stockage des templates.
     *
     * @param string $name Nom de qualification
     *
     * @return Folder|null
     */
    public function getFolder(string $name): ?Folder;

    /**
     * Récupération du chemin absolu vers le répertoire de surchage des gabarits d'affichage.
     *
     * @param string $path Chemin relatif vers un sous dossier du répertoire de surcharge.
     *
     * @return string|null
     */
    public function getOverrideDir(string $path = ''): ?string;

    /**
     * Modification du répertoire de stockage de gabarit d'affichage personnalisé existant.
     *
     * @param string $name Nom de qualification du répertoire de stockage personnalisé.
     * @param string  $directory Chemin vers le répertoire de stockage du groupe.
     * @param null|boolean $fallback Activation du parcours du répertoire principal si le un gabarit appelé est
     * manquant. si null prend la valeur d'activation du répertoire supprimé.
     *
     * @return static
     */
    public function modifyFolder(string $name, string $directory, ?bool $fallback = null): PlatesEngine;

    /**
     * Définition du nom de qualification du gestionnaire de gabarit.
     *
     * @param string $factory Nom de qualification.
     *
     * @return static
     */
    public function setFactory(string $factory): PlatesEngine;

    /**
     * Définition d'une liste de répertoire de stockage des gabarits.
     *
     * @param string[] $folders
     *
     * @return static
     */
    public function setFolders(array $folders): PlatesEngine;

    /**
     * Définition du repertoire de surcharge des gabarits d'affichages.
     *
     * @param string $dir Chemin absolu vers le répertoire de surcharge.
     *
     * @return static
     */
    public function setOverrideDir(string $dir): PlatesEngine;
}