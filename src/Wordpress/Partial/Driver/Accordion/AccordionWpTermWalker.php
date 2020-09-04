<?php declare(strict_types=1);

namespace tiFy\Wordpress\Partial\Driver\Accordion;

use tiFy\Partial\Driver\Accordion\AccordionWalkerItems;
use WP_Term;
use WP_Term_Query;

class AccordionWpTermWalker extends AccordionWalkerItems
{
    /**
     * CONSTRUCTEUR.
     *
     * @param AccordionWpTerm[]|WP_Term[] $terms Liste des éléments.
     * @param array|null $opened Liste des éléments ouverts.
     *
     * @return void
     */
    public function __construct($terms, $opened = null)
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

        parent::__construct($items, $opened);
    }

    /**
     * @inheritDoc
     *
     * @param WP_Term_Query|array $args Requête de récupération de termes ou liste des arguments de requête de
     *                                  récupération.
     *                                  @see https://developer.wordpress.org/reference/classes/wp_term_query/
     */
    public static function query($args, $opened = null): self
    {
        if (!$args instanceof WP_Term_Query) {
            $args = new WP_Term_Query($args);
        }

        return new static($args->terms ? : [], $opened);
    }
}