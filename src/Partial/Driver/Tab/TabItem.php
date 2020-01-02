<?php declare(strict_types=1);

namespace tiFy\Partial\Driver\Tab;

use Closure;
use tiFy\Contracts\Partial\TabItems;
use tiFy\Contracts\Partial\TabItem as TabItemContract;
use tiFy\Support\HtmlAttrs;
use tiFy\Support\ParamsBag;

class TabItem extends ParamsBag implements TabItemContract
{
    /**
     * Identifiant de qualification.
     * @var string
     */
    private $_id;

    /**
     * Identifiant d'indexation.
     * @var int
     */
    private $_index = 0;

    /**
     * Indicateur d'élément actif.
     * @var bool
     */
    protected $active = false;

    /**
     * Niveau de profondeur dans l'interface d'affichage.
     * @var int
     */
    protected $depth = 0;

    /**
     * Instance du gestionnaire d'éléments.
     * @var TabItems
     */
    protected $manager;

    /**
     * Instance de l'élément parent.
     * @var TabItemContract|false
     */
    protected $parent;

    /**
     * Génération des identifiants de qualification.
     *
     * @return $this
     */
    private function _generateIds(): TabItemContract
    {
        $this->_index = $this->manager->getItemIndex();
        $this->_id = "tab-{$this->manager->getIndex()}--{$this->_index}";

        $name = $this->get('name', null);
        if (!$name || !is_string($name)) {
            $this->set('name', $this->_id);
        }
        return $this;
    }

    /**
     * Résolution de sortie la classe sous forme de chaîne de caractère.
     *
     * @return string
     */
    public function __toString(): string
    {
        return $this->getName();
    }

    /**
     * @inheritdoc
     */
    public function defaults(): array
    {
        return [
            'content'  => '',
            'name'     => '',
            'parent'   => null,
            'position' => null,
            'title'    => ''
        ];
    }


    /**
     * @inheritdoc
     */
    public function getId(): string
    {
        return $this->_id;
    }

    /**
     * @inheritdoc
     */
    public function getIndex(): int
    {
        return $this->_index;
    }

    /**
     * @inheritdoc
     */
    public function getChilds(): iterable
    {
        return $this->manager->getGrouped($this->getName());
    }

    /**
     * @inheritdoc
     */
    public function getContent(): string
    {
        $content = $this->get('content', '');
        return $content instanceof Closure ? call_user_func($content) : (string)$content;
    }

    /**
     * @inheritdoc
     */
    public function getContentAttrs($linearized = true): string
    {
        $attrs = [
            'id'           => $this->getId(),
            'class'        => 'Tab-contentPane' . ($this->active ? ' active show' : ''),
            'data-name'    => $this->getName(),
            'data-control' => 'tab.content.pane',
            'role'         => 'tabpanel'
        ];
        return HtmlAttrs::createFromAttrs($attrs, $linearized);
    }

    /**
     * @inheritdoc
     */
    public function getDepth(): int
    {
        return $this->depth;
    }

    /**
     * @inheritdoc
     */
    public function getName(): string
    {
        return $this->get('name');
    }

    /**
     * @inheritdoc
     */
    public function getNavAttrs($linearized = true): string
    {
        $attrs = [
            'class'         => 'Tab-navLink' . ($this->active ? ' active' : ''),
            'aria-selected' => $this->active ? 'true' : 'false',
            'data-control'  => 'tab.nav.link',
            'data-name'     => $this->getName(),
            'data-toggle'   => 'tab',
            'href'          => "#{$this->getId()}",
            'role'          => 'tab'
        ];
        return HtmlAttrs::createFromAttrs($attrs, $linearized);
    }

    /**
     * @inheritdoc
     */
    public function getParent(): ?TabItemContract
    {
        if (is_null($this->parent)) {
            if ($name = $this->get('parent', null)) {
                $this->parent = $this->manager->get($name) ?: false;
            } else {
                $this->parent = false;
            }
        }
        return $this->parent ?: null;
    }

    /**
     * @inheritdoc
     */
    public function getTitle(): string
    {
        return (string)$this->get('title', '');
    }

    /**
     * @inheritdoc
     */
    public function prepare(TabItems $manager): TabItemContract
    {
        if (!$this->manager instanceof TabItems) {
            $this->manager = $manager;
        }
        return $this->_generateIds();
    }

    /**
     * @inheritdoc
     */
    public function setActivation(?string $active): TabItemContract
    {
        if (!is_null($active)) {
            if ($this->getName() === $active) {
                $this->active = true;
                if ($parent = $this->getParent()) {
                    $parent->setActivation($parent->getName());
                }
            }
        }
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function setDepth(int $depth = 0): TabItemContract
    {
        $this->depth = $depth;

        return $this;
    }
}