<?php declare(strict_types=1);

namespace tiFy\Partial\Driver\CurtainMenu;

use tiFy\Contracts\Partial\{CurtainMenuItem as CurtainMenuItemContract, CurtainMenuItems};
use tiFy\Support\{HtmlAttrs, ParamsBag};

class CurtainMenuItem extends ParamsBag implements CurtainMenuItemContract
{
    /**
     * Liste des éléments enfants associés.
     * @var CurtainMenuItemContract[]|null
     */
    protected $childs;

    /**
     * Nom de qualification de l'élément.
     * @var string
     */
    protected $name = '';

    /**
     * Instance du gestionnaire d'éléments.
     * @var CurtainMenuItems
     */
    protected $manager;

    /**
     * CONSTRUCTEUR.
     *
     * @param string $name Nom de qualification de l'élément.
     * @param array $attrs Liste des attributs de configuration.
     *
     * @return void
     */
    public function __construct(string $name, array $attrs = [])
    {
        $this->name = $name;

        $this->set($attrs);
    }

    /**
     * @inheritDoc
     */
    public function defaults(): array
    {
        return [
            'attrs'    => [],
            'back'     => [],
            'content'  => [],
            'depth'    => 0,
            'parent'   => null,
            'selected' => false,
            'url'      => '',
            'title'    => [],
        ];
    }

    /**
     * @inheritDoc
     */
    public function hasChild(): bool
    {
        return $this->getChilds() !== null;
    }

    /**
     * @inheritDoc
     */
    public function hasParent(): bool
    {
        return $this->getParent() !== null;
    }

    /**
     * @inheritDoc
     */
    public function getAttrs(bool $linearized = true)
    {
        return HtmlAttrs::createFromAttrs($this->get('attrs', []), $linearized);
    }

    /**
     * @inheritDoc
     */
    public function getBack(): array
    {
        return $this->get('back', []);
    }

    /**
     * @inheritDoc
     */
    public function getChilds(): ?array
    {
        if (is_null($this->childs)) {
            $this->childs = $this->manager->getParentItems($this->getName());
        }
        return $this->childs ?: null;
    }

    /**
     * @inheritDoc
     */
    public function getDepth(): int
    {
        return (int)$this->get('depth', 0);
    }

    /**
     * @inheritDoc
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @inheritDoc
     */
    public function getParent(): ?CurtainMenuItemContract
    {
        return ($name = $this->getParentName()) && ($parent = $this->manager->get($name)) ? $parent : null;
    }

    /**
     * @inheritDoc
     */
    public function getParentName(): ?string
    {
        return $this->get('parent');
    }

    /**
     * @inheritDoc
     */
    public function getNav(): array
    {
        return $this->get('nav', []);
    }

    /**
     * @inheritDoc
     */
    public function getTitle(): array
    {
        return $this->get('title', []);
    }

    /**
     * @inheritDoc
     */
    public function isSelected(): bool
    {
        return (bool)$this->get('selected', false);
    }

    /**
     * @inheritDoc
     */
    public function setDepth(int $depth): CurtainMenuItemContract
    {
        $this->set('depth', $depth);

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setManager(CurtainMenuItems $manager): CurtainMenuItemContract
    {
        $this->manager = $manager;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setSelected($selected = false): CurtainMenuItemContract
    {
        $this->set('selected', $selected);

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function parse(): CurtainMenuItemContract
    {
        parent::parse();

        $this->set('attrs.data-control', 'curtain-menu.item');
        $this->set('attrs.class', 'CurtainMenu-item');

        $this->set('attrs.aria-parent', $this->hasParent() ? 'true' : 'false');
        $this->set('attrs.aria-child', $this->hasChild() ? 'true' : 'false');

        $back = $this->get('back', []);
        if (is_string($back)) {
            $back = ['content' => $back];
        }
        $this->set('back', array_merge([
            'attrs'   => [
                'class' => 'CurtainMenu-itemBack',
                'href'  => '#',
            ],
            'content' => __('Retour', 'tify'),
            'tag'     => 'a',
        ], $back));
        $this->set('back.attrs.data-control', 'curtain-menu.back');

        $nav = $this->get('nav', []);
        if (is_string($nav)) {
            $nav = ['content' => $nav];
        }
        $this->set('nav', array_merge([
            'attrs'   => [
                'href'  => $this->get('url') ? : '#',
                'class' => 'CurtainMenu-itemNav',
            ],
            'content' => $this->getName(),
            'tag'     => 'a',
        ], $nav));
        $this->set('nav.attrs.data-control', 'curtain-menu.nav');

        $title = $this->get('title', []);
        if (is_string($title)) {
            $title = ['content' => $title];
        }
        $this->set('title', array_merge([
            'attrs'   => [
                'class' => 'CurtainMenu-itemTitle',
            ],
            'content' => $this->getName(),
            'tag'     => 'h3',
        ], $title));
        $this->set('title.attrs.data-control', 'curtain-menu.title');

        return $this;
    }
}