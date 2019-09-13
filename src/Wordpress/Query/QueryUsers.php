<?php declare(strict_types=1);

namespace tiFy\Wordpress\Query;

use tiFy\Support\Collection;
use tiFy\Wordpress\Contracts\QueryUsers as QueryUsersContract;
use WP_User;
use WP_User_Query;

class QueryUsers extends Collection implements QueryUsersContract
{
    /**
     * Instance de la requête Wordpress de récupération des utilisateurs.
     * @var WP_User_Query
     */
    protected $wp_user_query;

    /**
     * CONSTRUCTEUR.
     *
     * @param WP_User_Query|null $wp_user_query Instance de requête Wordpress de récupération des utilisateurs.
     *
     * @return void
     */
    public function __construct(?WP_User_Query $wp_user_query = null)
    {
        if ($this->wp_user_query = $wp_user_query) {
            $this->set($this->wp_user_query->get_results() ?: []);
        }
    }

    /**
     * @inheritdoc
     */
    public static function createFromArgs(array $args): QueryUsersContract
    {
        return new static(new WP_User_Query($args));
    }

    /**
     * @inheritdoc
     */
    public static function createFromIds(array $ids): QueryUsersContract
    {
        return new static(new WP_User_Query(['include' => $ids]));
    }

    /**
     * @inheritdoc
     */
    public function getIds(): array
    {
        return $this->pluck('ID');
    }

    /**
     * @inheritdoc
     */
    public function getDisplayNames(): array
    {
        return $this->pluck('display_name');
    }

    /**
     * @inheritdoc
     */
    public function getEmails(): array
    {
        return $this->pluck('user_email');
    }

    /**
     * @inheritdoc
     */
    public function getLogins(): array
    {
        return $this->pluck('user_login');
    }

    /**
     * @inheritdoc
     */
    public function WpUserQuery(): WP_User_Query
    {
        return $this->wp_user_query;
    }

    /**
     * {@inheritdoc}
     *
     * @param WP_User $item Objet utilisateur Wordpress.
     *
     * @return void
     */
    public function walk($item, $key = null)
    {
        $this->items[$key] = new QueryUser($item);
    }
}