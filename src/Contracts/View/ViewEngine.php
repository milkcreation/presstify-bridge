<?php declare(strict_types=1);

namespace tiFy\Contracts\View;

use Exception;
use League\Plates\{
    Extension\ExtensionInterface,
    Template\Folders,
    Template\Func,
    Engine
};
use Throwable;
use tiFy\Contracts\Support\ParamsBag;

/**
 * @mixin Engine
 */
interface ViewEngine
{
    /**
     * Déclaration de données associées aux gabarits d'affichage.
     * {@internal Permet de définir des données associées à un gabarit spécifique|à une liste de gabarits|
     * à l'ensemble des gabarits}
     *
     * @param array $data Liste des données à associer.
     * @param null|string|array $templates Nom de qualification des gabarits en relation.
     *
     * @return static
     */
    public function addData(array $data, $templates = null);

    /**
     * Déclaration d'un nouveau répertoire de stockage personnalisé de gabarits d'affichage.
     *
     * @param string $name Nom de qualification du répertoire de stockage personnalisé.
     * @param string  $directory Chemin vers le répertoire de stockage du groupe.
     * @param boolean $fallback Activation du parcours du répertoire principal si le un gabarit appelé est manquant.
     *
     * @return static
     */
    public function addFolder($name, $directory, $fallback = false);

    /**
     * Vérification d'existance d'une fonction (macro) instanciable dans les gabarits d'affichage.
     *
     * @param string $name Nom de qualification de la fonction.
     *
     * @return boolean
     */
    public function doesFunctionExist($name);

    /**
     * Suppression d'une fonction (macro) instanciable dans les gabarits d'affichage.
     *
     * @param string $name Nom de qualification de la fonction.
     *
     * @return static
     */
    public function dropFunction($name);

    /**
     * Vérification d'existance d'un gabarit d'affichage.
     *
     * @param string $name Nom de qualification du gabarit d'affichage.
     *
     * @return boolean
     */
    public function exists($name);

    /**
     * Récupération dun controleur d'une fonction (macro) instanciable dans les gabarits d'affichage.
     *
     * @param string $name Nom de qualification de la fonction.
     *
     * @return Func
     */
    public function getFunction($name);

    /**
     * Récupération d'une instance de controleur d'un gabarit d'affichage.
     *
     * @param string $name Nom de qualification du gabarit.
     *
     * @return ViewController
     */
    public function getController($name);

    /**
     * Récupération du chemin absolu vers le répertoire de surchage des gabarits d'affichage.
     *
     * @param string $path Chemin relatif vers un sous dossier du répertoire de surcharge.
     *
     * @return string
     */
    public function getOverrideDir($path = '');

    /**
     * Récupérations de toutes les données associées aux gabarits d'affichage.
     * {@internal Permet de récupération la listes des données globales|spécifique à un gabarit d'affichage.}
     *
     * @param  null|string $template Nom de qualification du gabarit spécifique.
     *
     * @return array
     */
    public function getData($template = null);

    /**
     * Récupération du chemin vers le répertoire principal de stockage des gabarits d'affichage.
     *
     * @return string
     */
    public function getDirectory();

    /**
     * Récupération de l'extension des fichiers de gabarit d'affichage.
     *
     * @return string
     */
    public function getFileExtension();

    /**
     * Récupération de la liste des répertoires de stockage de gabarit d'affichage personnalisés.
     *
     * @return Folders
     */
    public function getFolders();

    /**
     * Instanciation (Chargement) d'une extention (plugin).
     *
     * @param ExtensionInterface $extension Classe de rappel de l'extension
     *
     * @return static
     */
    public function loadExtension(ExtensionInterface $extension);

    /**
     * Instanciation (Chargement) de plusieurs extention (plugins).
     *
     * @param ExtensionInterface[] $extensions Liste des classes de rappel des extensions
     *
     * @return static
     */
    public function loadExtensions(array $extensions = []);

    /**
     * Déclaration d'un nouveau gabarit d'affichage.
     *
     * @param string $name Nom de qualification du gabarit d'affichage.
     * @param array $data Liste des variables passées en argument et accessible depuis le gabarit.
     *
     * @return ViewController
     */
    public function make($name, $data = []);

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
    public function modifyFolder($name, $directory, $fallback = null);

    /**
     * Récupération de paramètre|Définition de paramètres|Instance du gestionnaire de paramètre.
     *
     * @param string|array|null $key Clé d'indice du paramètre à récupérer|Liste des paramètre à définir.
     * @param mixed $default Valeur de retour par défaut lorsque la clé d'indice est une chaine de caractère.
     *
     * @return mixed|ParamsBag
     */
    public function params($key = null, $default = null);

    /**
     * Récupération du chemin absolu vers un gabarit d'affichage.
     *
     * @param string $name Nom de qualification du gabarit d'affichage.
     *
     * @return string
     */
    public function path($name);

    /**
     * Déclaration d'une fonction (macro) instanciable dans les gabarits d'affichage.
     *
     * @param string $name Nom de qualification (d'appel dans les gabarits) de la fonction.
     * @param callback $callback Instance de la fonction.
     *
     * @return static
     */
    public function registerFunction($name, $callback);

    /**
     * Suppression d'un répertoire de stockage de gabarit d'affichage personnalisé.
     *
     * @param string $name Nom de qualification du répertoire.
     *
     * @return static
     */
    public function removeFolder($name);

    /**
     * Déclaration d'un nouveau gabarit d'affichage et traitement du rendu.
     *
     * @param string $name Nom de qualification du gabarit d'affichage.
     * @param array $data Liste des variables passées en argument et accessible depuis le gabarit.
     *
     * @return string
     *
     * @throws Throwable
     * @throws Exception
     */
    public function render($name, array $data = []);

    /**
     * Définition de données partagées entre les gabarits.
     *
     * @param array|string $key
     * @param mixed|null $value
     *
     * @return static
     */
    public function share($key, $value = null): ViewEngine;

    /**
     * Définition du nom de qualification du controleur de gabarits d'affichage.
     *
     * @param string $controller Nom de qualification de la classe.
     *
     * @return static
     */
    public function setController($controller);

    /**
     * Définition du chemin vers le répertoire principal de stockage des gabarits d'affichage.
     * @param  string|null $directory Chemin vers le répertoire principal de stockage des gabarits d'affichage.
     *                                Mettre à null pour désactiver.
     *
     * @return static
     */
    public function setDirectory($directory);

    /**
     * Définition de l'extension des fichiers de gabarit d'affichage.
     *
     * @param  string|null $fileExtension Extension des fichiers de gabarit d'affichage. Mettre à null pour
     *                                    personnaliser.
     *
     * @return static
     */
    public function setFileExtension($fileExtension);

    /**
     * Définition du repertoire de surcharge des gabarits d'affichages.
     *
     * @param string $dir Chemin absolu vers le répertoire de surcharge.
     *
     * @return static
     */
    public function setOverrideDir($dir);

    /**
     * Définition d'un attribut de configuration.
     *
     * @param string $key Clé d'indexe de l'attribut. Syntaxe à point permise.
     * @param mixed $value Valeur de l'attribut.
     *
     * @return static
     */
    public function setParam($key, $value);
}