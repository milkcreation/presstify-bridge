<?php declare(strict_types=1);

namespace tiFy\Partial\Partials\Breadcrumb;

use tiFy\Contracts\Partial\Breadcrumb as BreadcrumbContract;
use tiFy\Contracts\Partial\PartialFactory as PartialFactoryContract;
use tiFy\Partial\PartialFactory;

class Breadcrumb extends PartialFactory implements BreadcrumbContract
{
    /**
     * Liste des éléments contenus dans le fil d'ariane
     * @var array
     */
    protected $parts = [];

    /**
     * Indicateur de désactivation d'affichage du fil d'ariane
     * @var bool
     */
    private $disabled = false;

    /**
     * @inheritDoc
     */
    public function addPart($part)
    {
        array_push($this->parts, $part);

        return $this;
    }

    /**
     * {@inheritDoc}
     *
     * @return array {
     *      @var array $attrs Attributs HTML du champ.
     *      @var string $after Contenu placé après le champ.
     *      @var string $before Contenu placé avant le champ.
     *      @var array $viewer Liste des attributs de configuration du pilote d'affichage.
     *      @var string[]|array[]|object[]|callable[] $parts Liste des élements du fil d'ariane.
     * }
     */
    public function defaults(): array
    {
        return [
            'attrs'  => [],
            'after'  => '',
            'before' => '',
            'viewer' => [],
            'parts'  => [],
        ];
    }

    /**
     * @inheritDoc
     */
    public function disable()
    {
        $this->disabled = true;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function display(): string
    {
        if ($this->disabled) {
            return '';
        }

        $this->set('items', $this->parsePartList());

        return parent::display();
    }

    /**
     * @inheritDoc
     */
    public function enable()
    {
        $this->disabled = false;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function parsePartList()
    {
        if (!$this->parts) {
            $this->parts = (new WpQueryPart())->getList();
        }

        $parts = [];
        foreach($this->parts as $part) {
            $parts[] = $this->parsePart($part);
        }

        return $parts;
    }

    /**
     * @inheritDoc
     */
    public function parsePart($part)
    {
        if (is_string($part)) {
            return $part;
        } elseif (is_object($part) && is_string((string) $part)) {
            return (string)$part;
        } elseif (is_array($part)) {
            $defaults = [
                'class'   => 'Breadcrumb-item',
                'content' => ''
            ];
            $part = array_merge($defaults, $part);

            return "<li class=\"{$part['class']}\">{$part['content']}</li>";
        }

        return '';
    }

    /**
     * @inheritDoc
     */
    public function prependPart($part)
    {
        array_unshift($this->parts, $part);

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function reset()
    {
        $this->parts = [];

        return $this;
    }
}