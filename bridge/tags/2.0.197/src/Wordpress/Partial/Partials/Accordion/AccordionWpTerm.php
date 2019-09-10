<?php

namespace tiFy\Wordpress\Partial\Partials\Accordion;

use tiFy\Partial\Partials\Accordion\AccordionItem;
use WP_Term;

class AccordionWpTerm extends AccordionItem
{
    /**
     * Terme de taxonomie associé
     * @var WP_Term
     */
    protected $term;

    /**
     * CONSTRUCTEUR.
     *
     * @param string $name Nom de qualification de l'élément.
     * @param WP_Term $attrs Liste des attributs de configuration.
     *
     * @return void
     */
    public function __construct($name, WP_Term $term)
    {
        $this->term = $term;

        $attrs = get_object_vars($term);

        parent::__construct($name, $attrs);
    }

    /**
     * {@inheritdoc}
     */
    public function defaults()
    {
        return [
            'parent'     => null,
            'content'    => $this->term->name,
            'attrs'      => [],
            'depth'      => 0
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function getContent()
    {
        return (string) partial(
            'tag',
            [
                'tag' => 'a',
                'attrs' => [
                    'href' => get_term_link($this->term)
                ],
                'content' => $this->term->name
            ]
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return $this->get('parent');
    }


    /**
     * {@inheritdoc}
     */
    public function setDepth($depth)
    {
        $this->set('depth', $depth);

        return $this;
    }
}