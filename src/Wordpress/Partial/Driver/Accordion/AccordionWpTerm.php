<?php declare(strict_types=1);

namespace tiFy\Wordpress\Partial\Driver\Accordion;

use tiFy\Partial\Driver\Accordion\AccordionItem;
use tiFy\Wordpress\Contracts\Partial\AccordionWpTerm as AccordionWpTermContract;
use tiFy\Support\Proxy\Partial;
use WP_Term;

class AccordionWpTerm extends AccordionItem implements AccordionWpTermContract
{
    /**
     * Terme de taxonomie associé
     * @var WP_Term
     */
    protected $term;

    /**
     * CONSTRUCTEUR.
     *
     * @param string|int $name Nom de qualification de l'élément.
     * @param WP_Term $term Objet term Wordpress
     *
     * @return void
     */
    public function __construct($name, WP_Term $term)
    {
        $this->term = $term;

        parent::__construct($name, get_object_vars($this->term));
    }

    /**
     * @inheritDoc
     */
    public function defaults(): array
    {
        return [
            'attrs'  => [],
            'depth'  => 0,
            'parent' => null,
            'render' => $this->term()->name,
        ];
    }

    /**
     * @inheritDoc
     */
    public function render(): string
    {
        return Partial::get('tag', [
            'tag'     => 'a',
            'attrs'   => [
                'class' => 'Accordion-itemLink',
                'href'  => get_term_link($this->term()),
            ],
            'content' => $this->term()->name,
        ])->render();
    }

    /**
     * @inheritDoc
     */
    public function term(): WP_Term
    {
        return $this->term;
    }
}