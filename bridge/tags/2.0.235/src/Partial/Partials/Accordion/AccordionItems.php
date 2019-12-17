<?php declare(strict_types=1);

namespace tiFy\Partial\Partials\Accordion;

use Illuminate\Support\Arr;
use tiFy\Contracts\Partial\{
    Accordion,
    AccordionItem as AccordionItemContract,
    AccordionItems as AccordionItemsContract
};
use tiFy\Support\Collection;
use tiFy\Support\HtmlAttrs;

class AccordionItems extends Collection implements AccordionItemsContract
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
        array_walk($items, [$this, 'walk']);

        $this->setOpened($opened);
    }

    /**
     * @inheritDoc
     */
    public function __toString(): string
    {
        return (string)$this->render();
    }

    /**
     * @inheritDoc
     */
    public function render(): string
    {
        return $this->walker($this->items, 0, null);
    }

    /**
     * @inheritDoc
     */
    public function setOpened($opened = null): AccordionItemsContract
    {
        if (!is_null($opened)) {
            $opened = Arr::wrap($opened);

            $this->collect()->each(function (AccordionItem $item) use ($opened) {
                if (in_array($item->getName(), $opened)) {
                    $item->set('open', true);
                }
            });
        }

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setPartial(Accordion $partial): AccordionItemsContract
    {
        $this->partial = $partial;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function walker($items = [], $depth = 0, $parent = null): string
    {
        $opened = false;
        $output = '';

        foreach ($items as $item) {
            if ($item->getParent() !== $parent) {
                continue;
            }

            if (!$opened) {
                $output = "<ul class=\"Accordion-items Accordion-items--{$depth}\" data-control=\"accordion.items\">";
                $opened = true;
            }

            $item->setDepth($depth);

            $attrs = [
                'class'        => "Accordion-item Accordion-item--{$item->getName()}",
                'data-control' => 'accordion.item',
                'aria-open'    => $item->isOpen() ? 'true' : 'false'
            ];

            $output .= "<li " . HtmlAttrs::createFromAttrs($attrs) . ">";
            $output .= $this->partial->viewer('item', compact('item'));
            $output .= $this->walker($items, ($depth + 1), $item->getName());
            $output .= "</li>";
        }

        if ($opened) {
            $output .= "</ul>";
        }

        return $output;
    }

    /**
     * @inheritDoc
     */
    public function walk($item, $key = null): AccordionItemContract
    {
        if (!$item instanceof AccordionItem) {
            $item = new AccordionItem($key, $item);
        }

        return $this->items[$key] = $item;
    }
}