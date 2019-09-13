<?php

namespace tiFy\Form;

use tiFy\Contracts\Form\ButtonController as ButtonControllerContract;
use tiFy\Contracts\Form\FormFactory;
use tiFy\Form\Factory\ResolverTrait;
use tiFy\Kernel\Params\ParamsBag;

class ButtonController extends ParamsBag implements ButtonControllerContract
{
    use ResolverTrait;

    /**
     * Nom de qualification.
     * @var string
     */
    protected $name;

    /**
     * Attributs de configuration.
     * @var array
     */
    protected $attributes = [
        'label'           => '',
        'before'          => '',
        'after'           => '',
        'wrapper'         => true,
        'attrs'           => [],
        'grid'            => [],
        'type'            => '',
        'position'        => 0
    ];

    /**
     * CONSTRUCTEUR.
     *
     * @param string $name Nom de qualification.
     * @param array $attrs Liste des attributs de configuration.
     * @param FormFactory $form Instance du contrôleur de formulaire associé.
     *
     * @void
     */
    public function __construct($name, $attrs, FormFactory $form)
    {
        $this->name = $name;
        $this->form = $form;

        parent::__construct($attrs);

        $this->boot();
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
    public function boot()
    {

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
    public function getPosition()
    {
        return $this->get('position', 0);
    }

    /**
     * {@inheritdoc}
     */
    public function hasWrapper()
    {
        return !empty($this->get('wrapper'));
    }

    /**
     * {@inheritdoc}
     */
    public function parse($attrs = [])
    {
        parent::parse($attrs);

        // Attributs HTML de l'encapsuleur de champ.
        if ($wrapper = $this->get('wrapper')) :
            $wrapper = (is_array($wrapper)) ? $wrapper : [];
            $this->set('wrapper', array_merge(['tag' => 'div', 'attrs' => []], $wrapper));

            if (!$this->has('wrapper.attrs.id')) :
                $this->set('wrapper.attrs.id', "Form{$this->form()->index()}-button--{$this->getName()}");
            endif;
            if (!$this->get('wrapper.attrs.id')) :
                $this->pull('wrapper.attrs.id');
            endif;

            $default_class = "Form-button Form-button--{$this->getName()}";
            if (!$this->has('wrapper.attrs.class')) :
                $this->set('wrapper.attrs.class', $default_class);
            else :
                $this->set('wrapper.attrs.class', sprintf($this->get('wrapper.attrs.class', ''), $default_class));
            endif;
            if (!$this->get('wrapper.attrs.class')) :
                $this->pull('wrapper.attrs.class');
            endif;
        endif;

        // Activation de l'agencement des éléments.
        if ($this->form()->hasGrid()) :
            $grid = $this->get('grid', []);
            $prefix = $this->hasWrapper() ? 'wrapper.' : '';

            $grid = is_array($grid) ? $grid : [];
            $grid = array_merge(
                $this->form()->get('grid.defaults', []),
                $grid
            );

            foreach($grid as $k => $v) :
                $this->set("{$prefix}attrs.data-grid_{$k}", filter_var($v, FILTER_SANITIZE_STRING));
            endforeach;
        endif;
    }

    /**
     * {@inheritdoc}
     */
    public function render()
    {
        return field(
            'button',
            $this->all()
        );
    }
}