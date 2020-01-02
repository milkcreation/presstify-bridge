<?php declare(strict_types=1);

namespace tiFy\Form\Factory;

use tiFy\Contracts\Form\FactoryGroup;
use tiFy\Contracts\Form\FactoryGroups;
use tiFy\Support\HtmlAttrs;
use tiFy\Support\ParamsBag;

class Group extends ParamsBag implements FactoryGroup
{
    use ResolverTrait;

    /**
     * Identifiant d'indexation.
     * @var int
     */
    private $_index = 0;

    /**
     * Instance du gestionnaire des groupes de champ.
     * @var FactoryGroups
     */
    protected $manager;

    /**
     * Nom de qualification du groupe.
     * @var string
     */
    protected $name = '';

    /**
     * Instance du groupe parent.
     * @var FactoryGroup|null
     */
    protected $parent;

    /**
     * CONSTRUCTEUR.
     *
     * @param array $attrs Liste des attributs de configuration.
     *
     * @return void
     */
    public function __construct(array $attrs = [])
    {
        $this->set($attrs);
        if (!$this->name) {
            $this->name = strval($this->get('name'));
        }
    }

    /**
     * @inheritdoc
     */
    public function defaults()
    {
        return [
            'after'    => '',
            'before'   => '',
            'attrs'    => [],
            'parent'   => null,
            'position' => null
        ];
    }

    /**
     * @inheritdoc
     */
    public function after(): string
    {
        return $this->get('after');
    }

    /**
     * @inheritdoc
     */
    public function before(): string
    {
        return $this->get('before');
    }

    /**
     * @inheritdoc
     */
    public function getAttrs($linearized = true)
    {
        $attrs = $this->get('attrs', []);
        return $linearized ? HtmlAttrs::createFromAttrs($this->get('attrs', [])) : $attrs;
    }

    /**
     * @inheritdoc
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @inheritdoc
     */
    public function getFields(): iterable
    {
        return $this->fields()->fromGroup($this->getName()) ?: [];
    }

    /**
     * @inheritdoc
     */
    public function getChilds(): iterable
    {
        return $this->getName() ? $this->manager->getGrouped($this->getName()) : [];
    }

    /**
     * @inheritdoc
     */
    public function getPosition(): int
    {
        return (int)$this->get('position');
    }

    /**
     * @inheritdoc
     */
    public function getParent(): ?FactoryGroup
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
    public function parse(): FactoryGroup
    {
        $class = 'Form-fieldsGroup Form-fieldsGroup--' . $this->getName();
        if (!$this->has('attrs.class')) {
            $this->set('attrs.class', $class);
        } else {
            $this->set('attrs.class', sprintf($this->get('attrs.class'), $class));
        }
        $position = $this->getPosition();
        if (is_null($position)) {
            $position = $this->_index;
        }
        $this->set('position', intval($position));

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function prepare(FactoryGroups $manager): FactoryGroup
    {
        if (!$this->manager instanceof FactoryGroups) {
            $this->manager = $manager;
            $this->form = $this->manager->form();
        }
        $this->_index = $this->manager->getIncreasedIndex();

        $this->parse();

        return $this;
    }
}