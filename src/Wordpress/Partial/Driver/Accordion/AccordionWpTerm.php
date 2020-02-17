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
     * @param string $name Nom de qualification de l'élément.
     * @param WP_Term $term Liste des attributs de configuration.
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
            'attrs'   => [],
            'content' => $this->term->name,
            'depth'   => 0,
            'parent'  => null,
        ];
    }

    /**
     * @inheritDoc
     */
    public function getContent(): string
    {
        return (string)Partial::get('tag', [
            'tag'     => 'a',
            'attrs'   => [
                'class' => "AccordionItem-link",
                'href'  => get_term_link($this->term),
            ],
            'content' => $this->term->name,
        ]);
    }

    /**
     * @inheritDoc
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @inheritDoc
     */
    public function getParent()
    {
        return $this->get('parent') ?: null;
    }

    /**
     * @inheritDoc
     */
    public function setDepth($depth)
    {
        $this->set('depth', $depth);

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function wpTerm(): ?WP_Term
    {
        return $this->term;
    }
}