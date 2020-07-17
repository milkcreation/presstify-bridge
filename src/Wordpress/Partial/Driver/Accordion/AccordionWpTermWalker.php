<?php declare(strict_types=1);

namespace tiFy\Wordpress\Partial\Driver\Accordion;

use Illuminate\Support\Collection;
use tiFy\Partial\Driver\Accordion\AccordionWalker as BaseAccordionWalker;
use WP_Term;
use WP_Term_Query;

class AccordionWpTermWalker extends BaseAccordionWalker
{
    /**
     * Liste des éléments récupérer.
     * @var int[]|array|null
     */
    private static $fetched;

    /**
     * CONSTRUCTEUR.
     *
     * @param WP_Term[]|AccordionWpTerm[] $terms
     *
     * @return void
     */
    public function __construct($terms)
    {
        $items = [];
        array_walk($terms, function ($term, $key) use (&$items) {
            if ($term instanceof WP_Term) {
                $key = $term->term_id;

                $items[$key] = new AccordionWpTerm($key, $term);
            } elseif ($term instanceof AccordionWpTerm) {
                $items[$key] = $term;
            }
        });

        parent::__construct($items);
    }

    /**
     * Création d'une instance basée sur une liste d'arguments.
     *
     * @param WP_Term_Query|array $args
     * @param bool $with_parents Activation de la liste des parents associés (recommandé).
     *
     * @return static
     */
    public static function createFromArgs(array $args = [], bool $with_parents = true)
    {
        return new static(static::fetch($args, $with_parents));
    }

    /**
     * Récupération de la liste des terme de taxonomie.
     * @see https://developer.wordpress.org/reference/classes/wp_term_query/
     *
     * @param WP_Term_Query|array $args
     * @param bool $with_parents Activation de la liste des parents associés (recommandé).
     *
     * @return WP_Term[]|array
     */
    public static function fetch($args, bool $with_parents = true): array
    {
        static::$fetched = null;

        if (!$args instanceof WP_Term_Query) {
            $args = new WP_Term_Query($args);
        }

        /** @var WP_Term[] $terms */
        if ($terms = $args->get_terms()) {
            if ($with_parents) {
                foreach($terms as $term) {
                    /*if (isset($args['child_of']) && ($args['child_of'] == $term->parent)) {
                        continue;
                    }

                    if (isset($args['parent']) && ($args['parent'] == $term->parent)) {
                        continue;
                    }*/

                    static::fetchParents($term, $terms);
                }

                return $terms;
            } else {
                return $terms;
            }
        } else {
            return [];
        }
    }

    /**
     * Récupération recursive de la liste des termes parents associés à un terme enfant.
     *
     * @param WP_Term $term
     * @param WP_Term[]|array $exists
     *
     * @return void
     */
    public static function fetchParents(WP_Term $term, array &$exists = []): void
    {
        if (is_null(static::$fetched)) {
            static::$fetched = (new Collection($exists ? : []))->pluck('term_id')->all();
        }

        if (!isset(static::$fetched[$term->term_id])) {
            if ($term->parent && ($p = get_term($term->parent, $term->taxonomy)) && $p instanceof WP_Term) {
                array_push($exists, $p);

                if ($p->parent) {
                    static::fetchParents($p, $exists);
                }
            }
        }
    }
}