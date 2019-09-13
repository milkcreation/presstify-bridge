<?php declare(strict_types=1);

namespace tiFy\Wordpress\Query;

use tiFy\Wordpress\Contracts\QueryTerms as QueryTermsContract;
use tiFy\Support\Collection;
use WP_Term;
use WP_Term_Query;

class QueryTerms extends Collection implements QueryTermsContract
{
    /**
     * Instance de la requête de récupération des termes de Wordpress.
     * @var WP_Term_Query
     */
    protected $wp_term_query;

    /**
     * CONSTRUCTEUR.
     *
     * @param WP_Term_Query|null $wp_term_query Requête Wordpress de récupération de termes.
     *
     * @return void
     */
    public function __construct(?WP_Term_Query $wp_term_query = null)
    {
        if ($this->wp_term_query = $wp_term_query) {
            $this->set($this->wp_term_query->terms ? : []);
        }
    }

    /**
     * @inheritdoc
     */
    public static function createFromArgs($args = []): QueryTermsContract
    {
        return new static(new WP_Term_Query($args));
    }

    /**
     * @inheritdoc
     */
    public static function createFromIds(array $ids): QueryTermsContract
    {
        return new static(new WP_Term_Query(['include' => $ids]));
    }

    /**
     * @inheritdoc
     */
    public function getIds(): array
    {
        return $this->collect()->pluck('term_id')->all();
    }

    /**
     * @inheritdoc
     */
    public function getNames(): array
    {
        return $this->collect()->pluck('name')->all();
    }

    /**
     * @inheritdoc
     */
    public function getSlugs(): array
    {
        return $this->collect()->pluck('slug')->all();
    }

    /**
     * {@inheritdoc}
     *
     * @param WP_Term $item Objet terme Wordpress.
     *
     * @return void
     */
    public function walk($item, $key = null)
    {
        $this->items[$key] = new QueryTerm($item);
    }

    /**
     * @inheritdoc
     */
    public function WpTermQuery(): WP_Term_Query
    {
        return $this->wp_term_query;
    }
}