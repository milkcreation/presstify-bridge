<?php

namespace tiFy\Contracts\View;

use League\Plates\Extension\ExtensionInterface;
use League\Plates\Template\Folders;
use League\Plates\Template\Func;
use League\Plates\Engine;
/**
 * Interface ViewEngine
 * @package tiFy\Contracts\View
 *
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
     * @return self
     */
    public function addData(array $data, $templates = null);

    /**
     * Déclaration d'un nouveau répertoire de stockage personnalisé de gabarits d'affichage.
     *
     * @param string $name Nom de qualification du répertoire de stockage personnalisé.
     * @param string  $directory Chemin vers le répertoire de stockage du groupe.
     * @param boolean $fallback Activation du parcours du répertoire principal si le un gabarit appelé est manquant.
     *
     * @return self
     */
    public function addFolder($name, $directory, $fallback = false);

    /**
     * Récupération de la liste complète des attributs de configuration.
     *
     * @return array
     */
    public function all();

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
     * @return self
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
     * Récupération d'un attribut de configuration.
     *
     * @param string $key Clé d'indexe de l'attribut. Syntaxe à point permise.
     * @param mixed $default Valeur de retour par défaut.
     *
     * @return mixed
     */
    public function get($key, $default = '');

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
     * Vérification d'existance d'un attribut de configuration.
     *
     * @param string $key Clé d'indexe de l'attribut. Syntaxe à point permise.
     *
     * @return bool
     */
    public function has($key);

    /**
     * Instanciation (Chargement) d'une extention (plugin).
     *
     * @param ExtensionInterface $extension Classe de rappel de l'extension
     *
     * @return self
     */
    public function loadExtension(ExtensionInterface $extension);

    /**
     * Instanciation (Chargement) de plusieurs extention (plugins).
     *
     * @param ExtensionInterface[] $extensions Liste des classes de rappel des extensions
     *
     * @return self
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
     * @return self
     */
    public function modifyFolder($name, $directory, $fallback = null);

    /**
     * Récupération du chemin absolu vers un gabarit d'affichage.
     *
     * @param string $name Nom de qualification du gabarit d'affichage.
     *
     * @return string
     */
    public function path($name);

    /**
     * Définition d'un attribut de configuration.
     *
     * @param string $key Clé d'indexe de l'attribut. Syntaxe à point permise.
     * @param mixed $value Valeur de l'attribut.
     *
     * @return self
     */
    public function set($key, $value);

    /**
     * Définition du nom de qualification du controleur de gabarits d'affichage.
     *
     * @param string $controller Nom de qualification de la classe.
     *
     * @return self
     */
    public function setController($controller);

    /**
     * Définition du chemin vers le répertoire principal de stockage des gabarits d'affichage.
     * @param  string|null $directory Chemin vers le répertoire principal de stockage des gabarits d'affichage.
     *                                Mettre à null pour désactiver.
     *
     * @return self
     */
    public function setDirectory($directory);

    /**
     * Définition de l'extension des fichiers de gabarit d'affichage.
     *
     * @param  string|null $fileExtension Extension des fichiers de gabarit d'affichage. Mettre à null pour
     *                                    personnaliser.
     *
     * @return self
     */
    public function setFileExtension($fileExtension);

    /**
     * Définition du repertoire de surcharge des gabarits d'affichages.
     *
     * @param string $dir Chemin absolu vers le répertoire de surcharge.
     *
     * @return self
     */
    public function setOverrideDir($dir);

    /**
     * Déclaration d'une fonction (macro) instanciable dans les gabarits d'affichage.
     *
     * @param string $name Nom de qualification (d'appel dans les gabarits) de la fonction.
     * @param callback $callback Instance de la fonction.
     *
     * @return self
     */
    public function registerFunction($name, $callback);

    /**
     * Suppression d'un répertoire de stockage de gabarit d'affichage personnalisé.
     *
     * @param string $name Nom de qualification du répertoire.
     *
     * @return self
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
     * @throws \Throwable
     * @throws \Exception
     */
    public function render($name, array $data = []);
}