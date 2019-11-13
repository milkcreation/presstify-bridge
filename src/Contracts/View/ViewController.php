<?php declare(strict_types=1);

namespace tiFy\Contracts\View;

use League\Plates\Template\Template;

/**
 * @mixin Template
 */
interface ViewController
{
    /**
     * Récupération de la liste complète des attributs de configuration.
     *
     * @return array
     */
    public function all(): array;

    /**
     * Initialisation du controleur.
     *
     * @return void
     */
    public function boot(): void;

    /**
     * Récupération du répertoire du gabarit d'affichage courant.
     *
     * @return string
     */
    public function dirname(): string;

    /**
     * Récupération de l'instance du moteur de gabarits d'affichage.
     *
     * @return ViewEngine
     */
    public function engine(): ViewEngine;

    /**
     * Récupération d'un attribut de configuration.
     *
     * @param string $key Clé d'indexe de l'attribut. Syntaxe à point permise.
     * @param mixed $default Valeur de retour par défaut.
     *
     * @return mixed
     */
    public function get(string $key, $default = null);

    /**
     * Vérification d'existance d'un attribut de configuration.
     *
     * @param string $key Clé d'indexe de l'attribut. Syntaxe à point permise.
     *
     * @return boolean
     */
    public function has(string $key): bool;

    /**
     * Linéarisation d'une liste d'attributs HTML.
     *
     * @param array $attrs Liste des attributs HTML.
     * @param bool $linearized Activation de la linéarisation.
     *
     * @return string|array
     */
    public function htmlAttrs(array $attrs, bool $linearized = true);

    /**
     * Récupération et suppression d'un attribut de configuration.
     *
     * @param string $key Clé d'indexe de l'attribut. Syntaxe à point permise.
     * @param mixed $default Valeur de retour par défaut.
     *
     * @return mixed
     */
    public function pull(string $key, $default = null);

    /**
     * Réinitialisation du contenu d'une section.
     *
     * @param string $name Nom de qualification de la section.
     *
     * @return static
     */
    public function reset(string $name): ViewController;

    /**
     * Définition d'un argument partagé.
     *
     * @param array|string $key
     * @param mixed|null $value
     *
     * @return static
     */
    public function share($key, $value = null): ViewController;

    /**
     * Définition d'argument de gabarit.
     *
     * @param array|string $key
     * @param mixed|null $value
     *
     * @return static
     */
    public function set($key, $value): ViewController;
}