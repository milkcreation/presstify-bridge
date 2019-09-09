<?php

namespace tiFy\Partial\Partials\Accordion;

use Illuminate\Support\Arr;
use tiFy\Contracts\Partial\Accordion;
use tiFy\Contracts\Partial\AccordionItems as AccordionItemsContract;
use tiFy\Kernel\Collection\QueryCollection;
use tiFy\Support\HtmlAttrs;

class AccordionItems extends QueryCollection implements AccordionItemsContract
{
    /**
     * Instance du controleur d'affichage.
     * @var Accordion
     */
    protected $partial;

    /**
     * CONSTRUCTEUR.
     *
     * @param mixed $items Liste des éléments.
     * @param null $opened Liste des éléments ouverts.
     *
     * @return void
     */
    public function __construct($items, $opened = null)
    {
        $this->query($items);
        $this->setOpened($opened);
    }

    /**
     * {@inheritdoc}
     */
    public function __toString()
    {
        return (string)$this->render();
    }

    /**
     * {@inheritdoc}
     */
    public function query($args)
    {
        array_walk($args, [$this, 'wrap']);
    }

    /**
     * {@inheritdoc}
     */
    public function render()
    {
        return $this->walk($this->items, 0, null);
    }

    /**
     * {@inheritdoc}
     */
    public function setOpened($opened = null)
    {
        if (!is_null($opened)) :
            $opened = Arr::wrap($opened);

            $this->collect()->each(function (AccordionItem $item) use ($opened) {
                if (in_array($item->getName(), $opened)) :
                    $item->set('open', true);
                endif;
            });
        endif;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function setPartial(Accordion $partial)
    {
        $this->partial = $partial;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function walk($items = [], $depth = 0, $parent = null)
    {
        $opened = false;

        $output = "";
        foreach ($items as $item) :
            if ($item->getParent() !== $parent) :
                continue;
            endif;

            if (!$opened) :
                $output = "<ul class=\"Accordion-items Accordion-items--{$depth}\" data-control=\"accordion.items\">";
                $opened = true;
            endif;

            $item->setDepth($depth);

            $attrs = [
                'class'        => "Accordion-item Accordion-item--{$item->getName()}",
                'data-control' => 'accordion.item',
                'aria-open'    => $item->isOpen() ? 'true' : 'false'
            ];

            $output .= "<li ". HtmlAttrs::createFromAttrs($attrs) .">";
            $output .= $this->partial->viewer('item', compact('item'));
            $output .= $this->walk($items, ($depth + 1), $item->getName());
            $output .= "</li>";
        endforeach;

        if ($opened) :
            $output .= "</ul>";
        endif;

        return $output;
    }

    /**
     * {@inheritdoc}
     */
    public function wrap($item, $key = null)
    {
        if (!$item instanceof AccordionItem) {
            $item = new AccordionItem($key, $item);
        }

        return $this->items[$key] = $item;
    }
}