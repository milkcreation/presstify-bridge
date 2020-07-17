<?php declare(strict_types=1);

namespace tiFy\Partial\Driver\Accordion;

use tiFy\Contracts\Partial\{AccordionItem as AccordionItemContract, AccordionWalker as AccordionWalkerContract};
use tiFy\Support\ParamsBag;

class AccordionItem extends ParamsBag implements AccordionItemContract
{
    /**
     * Indicateur d'initialisation.
     * @var bool
     */
    private $built = false;

    /**
     * Identifiant de qualification de l'élément.
     * @var string|int
     */
    protected $id = '';

    /**
     * Instance du gestionnaire d'affichage de la liste des éléments.
     * @var AccordionWalkerContract
     */
    protected $walker;

    /**
     * CONSTRUCTEUR.
     *
     * @param string|int $id Identifiant de qualification de l'élément.
     * @param string|array $attrs Liste des attributs de configuration.
     *
     * @return void
     */
    public function __construct($id, $attrs)
    {
        $this->id = $id;

        if (is_string($attrs)) {
            $attrs = ['render' => $attrs];
        }

        $this->set($attrs);
    }

    /**
     * @inheritDoc
     */
    public function __toString(): string
    {
        return $this->render();
    }

    /**
     * @inheritDoc
     */
    public function build(): AccordionItemContract
    {
        if(!$this->built) {
            $this->parse();

            $this->built = true;
        }

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function defaults(): array
    {
        return [
            'parent'  => null,
            'attrs'   => [],
            'depth'   => 0,
            'open'    => false,
            'render'  => '',
        ];
    }

    /**
     * @inheritDoc
     */
    public function getDepth(): int
    {
        return $this->get('depth') ? : 0;
    }

    /**
     * @inheritDoc
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @inheritDoc
     */
    public function getParent()
    {
        return $this->get('parent', null) ? : null;
    }

    /**
     * @inheritDoc
     */
    public function isOpened(): bool
    {
        return (bool)$this->get('open');
    }

    /**
     * @inheritDoc
     */
    public function parse()
    {
        parent::parse();

        $this->set([
            'attrs.class'        => 'Accordion-itemContent',
            'attrs.data-control' => 'accordion.item.content',
        ]);

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function render(): string
    {
        return $this->get('render', '');
    }

    /**
     * @inheritDoc
     */
    public function setDepth(int $depth): AccordionItemContract
    {
        $this->set('depth', $depth);

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setOpened(bool $open = true): AccordionItemContract
    {
        $this->set('open', $open);

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setWalker(AccordionWalkerContract $walker): AccordionItemContract
    {
        $this->walker = $walker;

        return $this;
    }
}