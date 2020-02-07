<?php declare(strict_types=1);

namespace tiFy\Wordpress;

use tiFy\Wordpress\Contracts\Wordpress as WordpressContract;
use tiFy\Wordpress\Contracts\PostType;
use tiFy\Wordpress\Contracts\Taxonomy;
use tiFy\Wordpress\Contracts\Routing\Routing;
use tiFy\Wordpress\Contracts\User;
use tiFy\Wordpress\Contracts\WpQuery;
use tiFy\Wordpress\Contracts\WpScreen;
use WP_Screen;

class Wordpress implements WordpressContract
{
    /**
     * CONSTRUCTEUR
     *
     * @return void
     */
    public function __construct()
    {
        if ($this->is()) {
            config(['app_url' => site_url()]);
        }
    }

    /**
     * Résolution d'un service fourni.
     *
     * @param string $alias Nom de qualification du service fourni.
     * @param array $args Liste des variables passées en argument.
     *
     * @return mixed|null
     */
    private function _resolve($alias, ...$args)
    {
        return app()->get("wp.{$alias}", $args);
    }

    /**
     * @inheritdoc
     */
    public function is(): bool
    {
        return true;
    }

    /**
     * @inheritdoc
     */
    public function post_type(): ?PostType
    {
        return $this->_resolve('post_type');
    }

    /**
     * @inheritdoc
     */
    public function taxonomy(): ?Taxonomy
    {
        return $this->_resolve('taxonomy');
    }

    /**
     * @inheritdoc
     */
    public function routing(): ?Routing
    {
        return $this->_resolve('routing');
    }

    /**
     * @inheritdoc
     */
    public function user(): ?User
    {
        return $this->_resolve('user');
    }

    /**
     * @inheritdoc
     */
    public function wp_query(): ?WpQuery
    {
        return $this->_resolve('wp_query');
    }

    /**
     * @inheritdoc
     */
    public function wp_screen(?WP_Screen $wp_screen = null): WpScreen
    {
        return $this->_resolve('wp_screen', $wp_screen);
    }
}