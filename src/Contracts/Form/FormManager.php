<?php

namespace tiFy\Contracts\Form;

use tiFy\Contracts\Support\Manager;

interface FormManager extends Manager
{
    /**
     * Déclaration d'un addon.
     *
     * @param string $name Nom de qualification.
     * @param callable|object|string|null $concrete Fonction anonyme|Instance|Nom de classe du contrôleur.
     *
     * @return $this
     */
    public function addonRegister(string $name, $concrete = null): FormManager;

    /**
     * Déclaration d'un bouton.
     *
     * @param string $name Nom de qualification.
     * @param callable|object|string $concrete Fonction anonyme|Instance|Nom de classe du contrôleur.
     *
     * @return $this
     */
    public function buttonRegister($name, $concrete): FormManager;

    /**
     * Récupération ou définition du formulaire courant.
     *
     * @param string|FormFactory $form Nom de qualification ou instance du formulaire.
     *
     * @return FormFactory|null
     */
    public function current($form = null): ?FormFactory;

    /**
     * Déclaration d'un champ.
     *
     * @param string $name Nom de qualification.
     * @param callable|object|string $concrete Fonction anonyme|Instance|Nom de classe du contrôleur.
     *
     * @return $this
     */
    public function fieldRegister($name, $concrete): FormManager;

    /**
     * Récupération du numéro d'indice d'un formulaire déclaré.
     *
     * @param string $name Nom de qualification du formulaire.
     *
     * @return int|null
     */
    public function index($name): ?int;

    /**
     * Déclaration d'un formulaire.
     *
     * @param string $name Nom de qualification.
     * @param array $attrs Attributs de configuration.
     *
     * @return FormManager
     */
    public function register($name, ...$args): FormManager;

    /**
     * Réinitialisation du formulaire courant.
     *
     * @return FormManager
     */
    public function reset(): FormManager;

    /**
     * Récupération du chemin absolu vers le répertoire des ressources.
     *
     * @param string $path Chemin relatif du sous-repertoire.
     *
     * @return string
     */
    public function resourcesDir($path = ''): string;

    /**
     * Récupération de l'url absolue vers le répertoire des ressources.
     *
     * @param string $path Chemin relatif du sous-repertoire.
     *
     * @return string
     */
    public function resourcesUrl($path = ''): string;
}