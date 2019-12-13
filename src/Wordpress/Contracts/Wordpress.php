<?php declare(strict_types=1);

namespace tiFy\Wordpress\Contracts;

use tiFy\Wordpress\Contracts\Routing\Routing;
use WP_Screen;

interface Wordpress
{
    /**
     * Indicateur d'environnement Wordpress.
     *
     * @return boolean
     */
    public function is(): bool;

    /**
     * Récupération de l'instance du gestionnaire de type de post.
     *
     * @return PostType|null
     */
    public function post_type(): ?PostType;

    /**
     * Récupération de l'instance du gestionnaire de taxonomies.
     *
     * @return Taxonomy|null
     */
    public function taxonomy(): ?Taxonomy;

    /**
     * Récupération de l'instance du gestionnaire de taxonomies.
     *
     * @return Routing|null
     */
    public function routing(): ?Routing;

    /**
     * Récupération de l'instance du gestionnaire utilisateurs.
     *
     * @return User|null
     */
    public function user(): ?User;

    /**
     * Récupération de l'instance du gestionnaire utilisateurs.
     *
     * @return WpQuery|null
     */
    public function wp_query(): ?WpQuery;

    /**
     * Récupération de l'instance du gestionnaire utilisateurs.
     *
     * @return WpScreen|null
     */
    public function wp_screen(?WP_Screen $wp_screen = null): ?WpScreen;
}