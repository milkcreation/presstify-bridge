<?php declare(strict_types=1);

namespace tiFy\Wordpress\Contracts;

use WP_Screen;

interface WpScreen
{
    /**
     * Récupération de l'instance WP_Screen associée.
     *
     * @param WP_Screen|string $screen
     *
     * @return WPScreen|null
     */
    public static function get($screen = ''): ?WPScreen;

    /**
     * Récupération de l'identifiant de qualification de l'accroche de l'écran Wordpress.
     *
     * @return string
     */
    public function getHookname(): string;

    /**
     * Récupération du nom de qualification de l'objet Wordpress en relation.
     *
     * @return string
     */
    public function getObjectName(): string;

    /**
     * Récupération du type d'objet Wordpress en relation.
     *
     * @return string
     */
    public function getObjectType(): string;

    /**
     * Récupération de l'instance WP_Screen associée.
     *
     * @return WP_Screen|null
     */
    public function getScreen(): ?WP_Screen;

    /**
     * Vérification de correspondance avec l'écran d'affichage courant.
     *
     * @return boolean
     */
    public function isCurrent(): bool;

    /**
     * Traitement des attributs de configuration.
     *
     * @return WpScreen
     */
    public function parse(): WpScreen;
}