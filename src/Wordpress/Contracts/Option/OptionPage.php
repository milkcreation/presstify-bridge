<?php declare(strict_types=1);

namespace tiFy\Wordpress\Contracts\Option;

use tiFy\Contracts\{Support\ParamsBag, View\Engine as ViewEngine};

interface OptionPage extends ParamsBag
{
    /**
     * Résolution de sortie de la classe en tant que chaîne de caractère.
     *
     * @return string
     */
    public function __toString(): string;

    /**
     * Initialisation du controleur.
     *
     * @return void
     */
    public function boot(): void;

    /**
     * Récupération de l'identificant de qualification d'accroche.
     *
     * @return string
     */
    public function getHookname(): string;

    /**
     * Récupération du nom de qualification.
     *
     * @return string
     */
    public function getName(): string;

    /**
     * Vérifie si la page est un sous élement du menu "Réglages" de Wordpress.
     *
     * @return bool
     */
    public function isSettingsPage(): bool;

    /**
     * Traitement de la liste des arguments.
     *
     * @return static
     */
    public function parse(): OptionPage;

    /**
     * Déclaration des options associées à la page.
     *
     * @param array|string[] $settings
     *
     * @return static
     */
    public function registerSettings(array $settings): OptionPage;

    /**
     * Affichage.
     *
     * @return string
     */
    public function render(): string;

    /**
     * Définition du gestionnaire d'options.
     *
     * @param Option $manager Instance du gestionnaire d'options.
     *
     * @return static
     */
    public function setManager(Option $manager): OptionPage;

    /**
     * Définition du nom de qualification.
     *
     * @param string $name
     *
     * @return static
     */
    public function setName(string $name): OptionPage;

    /**
     * Récupération de la vue.
     * {@internal Si aucun argument n'est passé à la méthode, retourne l'intance du controleur principal.}
     * {@internal Sinon récupére le gabarit d'affichage et passe les variables en argument.}
     *
     * @param null|string view Nom de qualification du gabarit.
     * @param array $data Liste des variables passées en argument.
     *
     * @return ViewEngine|string
     */
    public function view(?string $view = null, array $data = []);
}