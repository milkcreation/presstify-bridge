<?php declare(strict_types=1);

namespace tiFy\Contracts\Options;

use tiFy\Contracts\{Support\ParamsBag, View\Engine as ViewEngine};
use WP_Screen;

interface OptionsPage extends ParamsBag
{
    /**
     * Résolution de sortie de la classe en tant que chaîne de caractère.
     *
     * @return string
     */
    public function __toString(): string;

    /**
     * Ajout d'un élément
     *
     * @param string $name Nom de qualification.
     * @param array $attrs Liste des attributs de configuration.
     *
     * @return static
     */
    public function add(string $name, array $attrs = []): OptionsPage;

    /**
     * Initialisation du controleur.
     *
     * @return void
     */
    public function boot(): void;

    /**
     * Liste des attributs de configuration par défaut.
     *
     * @return array
     */
    public function defaults(): array;

    /**
     * Récupération de l'identificant de qualification d'accroche.
     *
     * @return string
     */
    public function getHookname(): string;

    /**
     * Récupération de la liste des éléments déclarés.
     *
     * @return array
     */
    public function getItems(): array;

    /**
     * Récupération du nom de qualification.
     *
     * @return string
     */
    public function getName(): string;

    /**
     * Evénement de chargement de la page.
     *
     * @param WP_Screen $wp_screen
     *
     * @return void
     */
    public function load(WP_Screen $wp_screen): void;

    /**
     * Traitement de la liste des arguments.
     *
     * @return static
     */
    public function parse(): OptionsPage;

    /**
     * Traitement des attributs par default de configuration du menu d'administration.
     *
     * @return static
     */
    public function parseAdminMenu(): OptionsPage;

    /**
     * Traitement des attributs de configuration de la barre d'administration.
     *
     * @return static
     */
    public function parseAdminBar(): OptionsPage;

    /**
     * Traitement des attributs de configuration de la barre d'administration.
     *
     * @return static
     */
    public function parseItems(): OptionsPage;

    /**
     * Affichage.
     *
     * @return string
     */
    public function render(): string;

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
    public function viewer(?string $view = null, array $data = []);
}