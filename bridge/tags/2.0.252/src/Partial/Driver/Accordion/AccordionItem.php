<?php

namespace tiFy\Partial\Driver\Accordion;

use tiFy\Contracts\Partial\AccordionItem as AccordionItemContract;
use tiFy\Support\ParamsBag;

class AccordionItem extends ParamsBag implements AccordionItemContract
{
    /**
     * Nom de qualification de l'élément.
     * @var string
     */
    protected $name = '';

    /**
     * CONSTRUCTEUR.
     *
     * @param string $name Nom de qualification de l'élément.
     * @param string|array $attrs Liste des attributs de configuration.
     *
     * @return void
     */
    public function __construct($name, $attrs)
    {
        $this->name = $name;

        if (is_string($attrs)) {
            $attrs = ['content' => $attrs];
        }

        $this->set($attrs)->parse();
    }

    /**
     * {@inheritdoc}
     */
    public function defaults()
    {
        return [
            'parent'     => null,
            'content'    => '',
            'attrs'      => [],
            'depth'      => 0,
            'open'       => false
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function getContent()
    {
        return $this->get('content', '');
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
    public function isOpen()
    {
        return (bool)$this->get('open');
    }

    /**
     * {@inheritdoc}
     */
    public function setDepth($depth)
    {
        $this->set('depth', $depth);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function parse()
    {
        parent::parse();

        $this->set('attrs.data-control', 'accordion.item.content');

        $this->set('attrs.class', 'Accordion-itemContent');

        return $this;
    }
}