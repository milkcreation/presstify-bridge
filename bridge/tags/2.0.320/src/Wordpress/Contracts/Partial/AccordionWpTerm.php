<?php declare(strict_types=1);

namespace tiFy\Wordpress\Contracts\Partial;

use tiFy\Contracts\Partial\AccordionItem;
use WP_Term;

interface AccordionWpTerm extends AccordionItem
{
    /**
     * Récupération du terme Wordpress associé.
     *
     * @return WP_Term
     */
    public function term(): WP_Term;
}