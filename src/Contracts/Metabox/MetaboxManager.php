<?php declare(strict_types=1);

namespace tiFy\Contracts\Metabox;

use Psr\Container\ContainerInterface as Container;

interface MetaboxManager
{
    /**
     * Ajout d'une boîte de saisie.
     *
     * @param string $name Nom de qualification.
     * @param string|array|MetaboxDriver $metabox Nom de qualification du pilote|Attributs de configuration|Instance
     * du pilote.
     *
     * @return MetaboxDriver
     */
    public function add(string $name, $metabox): MetaboxDriver;

    /**
     * Ajout d'un écran d'affichage.
     *
     * @param string $name Nom de qualification.
     * @param string|array|MetaboxScreen $screen Nom de qualification de l'écran|Attributs de configuration de l'écran|
     * Instance de l'écran.
     *
     * @return MetaboxScreen
     */
    public function addScreen(string $name, $screen = []): MetaboxScreen;

    /**
     * Récupération de la liste des métaboxes déclarées.
     *
     * @return MetaboxDriver[]|array
     */
    public function all(): array;

    /**
     * Recupère les éléments de rendu pour un contexte associé à un écran d'affichage.
     * {@internal Utilise l'écran d'affichage courant, si l'écran d'affichage n'est pas défini.}
     *
     * @param MetaboxContext $context Instance du contexte de l'écran d'affichage.
     * @param MetaboxScreen|null $screen Instance de l'écran d'affichage.
     *
     * @return array
     */
    public function fetchRender(MetaboxContext $context, ?MetaboxScreen $screen = null): array;

    /**
     * Récupération du conteneur d'injection de dépendances.
     *
     * @return Container
     */
    public function getContainer(): Container;

    /**
     * Récupération de l'instance d'un contexte d'affichage.
     *
     * @param string $name Nom de qualification.
     *
     * @return MetaboxContext
     */
    public function getContext(string $name): MetaboxContext;

    /**
     * Récupération de l'instance d'un pilote de boîte de saisie.
     *
     * @param string $name Nom de qualification.
     *
     * @return MetaboxDriver
     */
    public function getDriver(string $name): MetaboxDriver;

    /**
     * Récupération de la liste des boîtes de saisie affichée pour un contexte d'affichage.
     *
     * @param string $context
     *
     * @return MetaboxDriver[]|array
     */
    public function getRenderItems(string $context): array;

    /**
     * Récupération de l'instance d'un écran d'affichage.
     *
     * @param string $name Nom de qualification.
     *
     * @return MetaboxScreen|null
     */
    public function getScreen(string $name): ?MetaboxScreen;

    /**
     * Déclaration d'un contexte d'affichage.
     *
     * @param string $name Nom de qualification.
     * @param MetaboxContext $context Instance du contexte.
     *
     * @return static
     */
    public function registerContext(string $name, MetaboxContext $context): MetaboxManager;

    /**
     * Déclaration d'un pilote de boîte de saisie.
     *
     * @param string $name Nom de qualification.
     * @param MetaboxDriver $driver Instance du pilote de boîte de saisie.
     *
     * @return static
     */
    public function registerDriver(string $name, MetaboxDriver $driver): MetaboxManager;

    /**
     * Récupération du rendu l'affichage des boîtes de saisies associées à un contexte d'un écran d'affichage.
     *
     * @param string $context Nom de qualification du contexte d'affichage.
     * @param array $args Tableau indexé d'arguments complémentaires.
     *
     * @return string
     */
    public function render(string $context, array $args = []): string;

    /**
     * Récupération du chemin absolu vers une ressource.
     *
     * @param string $path Chemin relatif de la ressource.
     *
     * @return string
     */
    public function resourcesDir(string $path = ''): string;

    /**
     * Récupération de l'url absolue vers une ressource.
     *
     * @param string $path Chemin relatif de la ressource.
     *
     * @return string
     */
    public function resourcesUrl(string $path = ''): string;

    /**
     * Déclaration d'un jeu de boîte de saisie boîte de saisie.
     *
     * @param string $screen Nom de qualification de l'écran d'affichage.
     * @param string $context Nom de qualification du contexte de l'écran d'affichage.
     * @param string[][]|array[][]|MetaboxDriver[][] $metaboxes Liste des boîtes de saisie.
     *
     * @return static
     */
    public function stack(string $screen, string $context, array $metaboxes): MetaboxManager;
}