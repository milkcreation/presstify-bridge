<?php

namespace tiFy\Form\Factory;

use Closure;
use Illuminate\Support\Arr;
use tiFy\Contracts\Form\FactoryField;
use tiFy\Contracts\Form\FieldController;
use tiFy\Contracts\Form\FormFactory;
use tiFy\Support\ParamsBag;

class Field extends ParamsBag implements FactoryField
{
    use ResolverTrait;

    /**
     * Liste des attributs de configuration.
     * @var array  {
     *
     * @var string $title Intitulé de qualification. Valeur par défaut. ex. label.
     * @var string $before Contenu HTML affiché avant le champ.
     * @var string $after Contenu HTML affiché après le champ.
     * @var bool|string|array $wrapper Affichage de l'encapuleur de champ. false si masqué|true charge les attributs par
     *                                 défaut|array permet de définir des attributs personnalisés.
     * @var bool|string|array $label Affichage de l'intitulé de champ. false si masqué|true charge les attributs par
     *                               défaut|array permet de définir des attributs personnalisés.
     * @var int $group Indice du groupe d'appartenance.
     * @var int $position Ordre d'affichage général ou dans le groupe s'il est défini.
     * @var string $type Type de champ.
     * @var string $name Indice de qualification de la variable de requête.
     * @var mixed $value Valeur courante de la variable de requête.
     * @var array $choices Liste de choix des valeurs multiples.
     * @var array $attrs Liste des attributs HTML. (hors name & value)
     * @var array $grid Attributs d'agencement du champ. La propriété doit être active au niveau du formulaire.
     * @var array $extras Liste des attributs complémentaires de configuration.
     * @var array $supports Définition des propriétés de support. label|wrapper|request|tabindex|transport.
     * @var boolean|string|array $required Configuration de champs requis. false si désactivé|true charge les attributs
     *                                     par défaut|array
     * {
     * @var boolean|string|array $tagged Affichage de l'indicateur de champ requis. false si masqué|true charge
     *                                        les attributs par défaut|string valeur de l'indicateur|array permet de
     *                                        définir des attributs personnalisés.
     * @var boolean $check Activation du test d'existance natif.
     * @var mixed $value_none Valeur à comparer pour le test d'existance.
     * @var string|callable $call Fonction de validation ou alias de qualification.
     * @var array $args Liste des variables passées en argument dans la fonction de validation.
     * @var boolean $raw Activation du format brut de la valeur.
     * @var string $message Message de notification de retour en cas d'erreur.
     * }
     * @var null|boolean $transport Court-circuitage (forçage) de la propriété de support du transport des données à
     *                              l'issue de la soumission.
     * @var array $validations {
     *      Liste des fonctions de validation d'intégrité du champ lors de la soumission.
     *
     * @var string|callable $call Fonction de validation ou alias de qualification.
     * @var array $args Liste des variables passées en arguments dans la fonction de validation.
     * @var string $message Message de notification d'erreur.
     * @var boolean $raw Activation du format brut de la valeur.
     * }
     * @var array $addons Liste des attributs de configuration associés aux addons.
     */
    protected $attributes = [
        'addons'      => [],
        'after'       => '',
        'attrs'       => [],
        'before'      => '',
        'choices'     => [],
        'extras'      => [],
        'grid'        => [],
        'group'       => 0,
        'label'       => true,
        'name'        => '',
        'position'    => 0,
        'required'    => false,
        'supports'    => [],
        'wrapper'     => null,
        'title'       => '',
        'transport'   => null,
        'type'        => 'html',
        'validations' => [],
        'value'       => ''
    ];

    /**
     * Valeur par défaut.
     * @var mixed
     */
    protected $default;

    /**
     * Indicateur du statut d'affichage de champ en erreur.
     * @var boolean
     */
    protected $error = false;

    /**
     * Identifiant de qualification du champ.
     * @var string
     */
    protected $slug = '';

    /**
     * CONSTRUCTEUR.
     *
     * @param string $slug Nom de qualification.
     * @param array $attrs Liste des attributs de configuration.
     * @param FormFactory $form Instance du contrôleur de formulaire.
     *
     * @return void
     */
    public function __construct($slug, $attrs, FormFactory $form)
    {
        $this->slug = $slug;
        $this->form = $form;

        $this->set($attrs)->parse();

        $this->events('field.init.' . $this->getSlug(), [&$this]);
        $this->events('field.init', [&$this]);
    }

    /**
     * @inheritdoc
     */
    public function __toString()
    {
        return $this->render();
    }

    /**
     * @inheritDoc
     */
    public function addError(string $message, array $data = []): FactoryField
    {
        $this->notices()->add('error', $message, array_merge($data, [
            'field' => $this->getSlug()
        ]));

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function after(): string
    {
        $after = $this->get('after', '');
        return $after instanceof Closure ? call_user_func($after) : strval($after);
    }

    /**
     * @inheritdoc
     */
    public function before(): string
    {
        $before = $this->get('before', '');
        return $before instanceof Closure ? call_user_func($before) : strval($before);
    }

    /**
     * @inheritdoc
     */
    public function defaults()
    {
        return [
            'name'  => $this->slug,
            'title' => $this->slug
        ];
    }

    /**
     * @inheritdoc
     */
    public function getAddonOption($name, $key = null, $default = null)
    {
        return (is_null($key))
            ? $this->get("addons.{$name}", [])
            : $this->get("addons.{$name}.{$key}", $default);
    }

    /**
     * @inheritdoc
     */
    public function getController()
    {
        return $this->resolve("field.{$this->getType()}.{$this->form()->name()}.{$this->getSlug()}");
    }

    /**
     * @inheritdoc
     */
    public function getExtras($key = null, $default = null)
    {
        return (is_null($key)) ? $this->get('extras', []) : $this->get("extras.{$key}", $default);
    }

    /**
     * @inheritdoc
     */
    public function getGroup()
    {
        return $this->fromGroup($this->get('group', ''));
    }

    /**
     * @inheritdoc
     */
    public function getName()
    {
        return $this->get('name');
    }

    /**
     * @inheritdoc
     */
    public function getPosition()
    {
        return $this->get('position', 0);
    }

    /**
     * @inheritdoc
     */
    public function getRequired($key = null, $default = null)
    {
        return $this->get('required' . ($key ? ".{$key}" : ''), $default);
    }

    /**
     * @inheritdoc
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * @inheritdoc
     */
    public function getTitle()
    {
        return $this->get('title');
    }

    /**
     * @inheritdoc
     */
    public function getType()
    {
        return $this->get('type');
    }

    /**
     * @inheritdoc
     */
    public function getValue($raw = true)
    {
        $value = $this->get('value');

        $this->form()->events('field.get.value', [&$value, $this]);

        if (!$raw) {
            $value = is_array($value) ? array_map('esc_attr', $value) : esc_attr($value);
        }

        return $value;
    }

    /**
     * @inheritdoc
     */
    public function getValues($raw = true, $glue = ', ')
    {
        $value = Arr::wrap($this->getValue());

        if ($choices = $this->get('choices', [])) {
            foreach ($value as &$v) {
                if (isset($choices[$v])) {
                    $v = $choices[$v];
                }
            }
        }

        if (!$raw) {
            $value = is_array($value) ? array_map('esc_attr', $value) : esc_attr($value);
        }

        if (!is_null($glue)) {
            $value = join($glue, $value);
        }

        return $value;
    }

    /**
     * @inheritdoc
     */
    public function hasLabel()
    {
        return $this->supports('label') && !empty($this->get('label'));
    }

    /**
     * @inheritdoc
     */
    public function hasWrapper()
    {
        return $this->supports('wrapper') && !empty($this->get('wrapper'));
    }

    /**
     * @inheritdoc
     */
    public function onError()
    {
        return ($this->supports('request') && !empty($this->notices()->query('error', ['field' => $this->getSlug()])))
            || !!$this->error;
    }

    /**
     * @inheritdoc
     */
    public function parseValidations($validations, $results = [])
    {
        if (is_array($validations)) :
            if (isset($validations['call'])) :
                $results[] = array_merge(
                    [
                        'call'    => '__return_true',
                        'args'    => [],
                        'message' => __('Le format du champ "%s" est invalide', 'tify'),
                        'raw'     => false
                    ],
                    $validations
                );
            else :
                foreach ($validations as $validation) :
                    $results += $this->parseValidations($validation, $results);
                endforeach;
            endif;
        elseif (is_string($validations)) :
            $validations = array_map('trim', explode(',', $validations));

            foreach ($validations as $call) :
                $results += $this->parseValidations(['call' => $call], $results);
            endforeach;
        endif;

        return $results;
    }

    /**
     * @inheritdoc
     */
    public function prepare()
    {
        $this->events('field.prepare.' . $this->getType(), [&$this]);
        $this->events('field.prepare', [&$this]);

        // Nom de qualification d'enregistrement de la requête.
        $name = $this->get('name', '');
        if (!is_null($name)) :
            $this->set('name', $name ? esc_attr($name) : esc_attr($this->getSlug()));
        endif;

        // Valeur par défaut.
        $this->default = $this->get('value', null);

        /**
         * Initialisation du controleur de champ.
         * @var FieldController $control
         */
        $control = (app()->has("form.field.{$this->getType()}"))
            ? $this->resolve("field.{$this->getType()}", [$name, $this])
            : $this->resolve("field", [$name, $this]);

        app()->share("form.field.{$this->getType()}.{$this->form()->name()}.{$this->getSlug()}", $control);

        // Propriétés de support.
        if (!$this->get('supports')) :
            $this->set('supports', $control->supports());
        endif;

        if ($this->get('transport') && !in_array('transport', $this->get('supports', []))) :
            $this->push('supports', 'transport');
        endif;

        if ($this->get('wrapper')) :
            $this->push('supports', 'wrapper');
        elseif (in_array('wrapper', $this->get('supports', []))) :
            $this->set('wrapper', true);
        endif;

        // Attributs de champ requis (marqueur et fonction de traitement).
        if ($required = $this->get('required', false)) :
            $required = (is_array($required))
                ? $required
                : (is_string($required) ? ['message' => $required] : []);

            $required = array_merge([
                'tagged'     => true,
                'check'      => true,
                'value_none' => '',
                'call'       => '',
                'args'       => [],
                'raw'        => true,
                'message'    => __('Le champ "%s" doit être renseigné.', 'tify'),
                'html5'      => false,
            ], $required);

            if ($tagged = $required['tagged']) :
                $tagged = is_array($tagged) ? $tagged : (is_string($tagged) ? ['content' => $tagged] : []);
                $required['tagged'] = array_merge([
                    'tag'     => 'span',
                    'attrs'   => [],
                    'content' => '*'
                ], $tagged);
            endif;

            $required['call'] = !empty($required['value_none']) && empty($required['call'])
                ? 'equals'
                : 'notEmpty';
            $required['args'] = !empty($required['value_none']) && empty($required['args'])
                ? [] + [$required['value_none']]
                : [];

            $this->set('required', $required);
        endif;

        // Liste des tests de validation.
        if ($validations = $this->get('validations')) :
            $this->set('validations', $this->parseValidations($validations));
        endif;

        foreach ($this->addons() as $name => $addon) {
            $this->set(
                "addons.{$name}",
                array_merge($addon->defaultsFieldOptions(), $this->get("addons.{$name}", []) ? : [])
            );
        }

        $this->events('field.prepared.' . $this->getType(), [&$this]);
        $this->events('field.prepared', [&$this]);
    }

    /**
     * @inheritdoc
     */
    public function resetValue()
    {
        return $this->set('value', $this->default);
    }

    /**
     * @inheritdoc
     */
    public function render()
    {
        return (string)$this->getController();
    }

    /**
     * @inheritdoc
     */
    public function renderPrepare()
    {
        // Attributs HTML du champ.
        if (!$this->has('attrs.id')) :
            $this->set('attrs.id', "Form{$this->form()->index()}-fieldInput--{$this->getSlug()}");
        endif;
        if (!$this->get('attrs.id')) :
            $this->pull('attrs.id');
        endif;

        $default_class = "Form-fieldInput Form-fieldInput--{$this->getType()} Form-fieldInput--{$this->getSlug()}";
        if (!$this->has('attrs.class')) :
            $this->set('attrs.class', $default_class);
        else :
            $this->set('attrs.class', sprintf($this->get('attrs.class', ''), $default_class));
        endif;
        if (!$this->get('attrs.class')) :
            $this->pull('attrs.class');
        endif;

        if (!$this->has('attrs.tabindex')) :
            $this->set('attrs.tabindex', $this->getPosition());
        endif;
        if ($this->get('attrs.tabindex') === false) :
            $this->pull('attrs.tabindex');
        endif;

        if ($this->onError()) :
            $this->set('attrs.aria-error', 'true');
        endif;

        // Attributs HTML de l'encapsuleur de champ.
        if ($wrapper = $this->get('wrapper')) :
            $wrapper = (is_array($wrapper)) ? $wrapper : [];
            $this->set('wrapper', array_merge(['tag' => 'div', 'attrs' => []], $wrapper));

            if (!$this->has('wrapper.attrs.id')) :
                $this->set('wrapper.attrs.id', "Form{$this->form()->index()}-field--{$this->getSlug()}");
            endif;
            if (!$this->get('wrapper.attrs.id')) :
                $this->pull('wrapper.attrs.id');
            endif;

            $default_class = "Form-field Form-field--{$this->getType()} Form-field--{$this->getSlug()}";
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

            foreach ($grid as $k => $v) :
                $this->set("{$prefix}attrs.data-grid_{$k}", filter_var($v, FILTER_SANITIZE_STRING));
            endforeach;
        endif;

        // Attributs HTML du libellé.
        if ($label = $this->get('label')) :
            $label = (is_array($label)) ? $label : [];
            $this->set('label', array_merge(['tag' => 'label', 'attrs' => [], 'wrapper' => false], $label));

            if (!$this->has('label.attrs.id')) :
                $this->set('label.attrs.id', "Form{$this->form()->index()}-fieldLabel--{$this->getSlug()}");
            endif;
            if (!$this->get('label.attrs.id')) :
                $this->pull('label.attrs.id');
            endif;

            $default_class = "Form-fieldLabel Form-fieldLabel--{$this->getType()} Form-fieldLabel--{$this->getSlug()}";
            if (!$this->has('label.attrs.class')) :
                $this->set('label.attrs.class', $default_class);
            else :
                $this->set('label.attrs.class', sprintf($this->get('label.attrs.class', ''), $default_class));
            endif;
            if (!$this->get('label.attrs.class')) :
                $this->pull('label.attrs.class');
            endif;

            if ($for = $this->get('attrs.id')) :
                $this->set('label.attrs.for', $for);
            endif;

            if (!$this->has('label.content')) :
                $this->set('label.content', $this->getTitle());
            endif;
            if (!$this->get('label.content')) :
                $this->pull('label.content');
            endif;

            if ($this->get('label.wrapper')) :
                $this->set('label.wrapper', [
                    'tag'   => 'div',
                    'attrs' => [
                        'id'    => "Form{$this->form()->index()}-fieldLabelWrapper--{$this->getSlug()}",
                        'class' => "Form-fieldLabelWrapper Form-fieldLabelWrapper--{$this->getType()}" .
                            " Form-fieldLabelWrapper--{$this->getSlug()}"
                    ]
                ]);
            endif;
        endif;

        if ($this->get('required.tagged')) :
            if (!$this->has('required.tagged.attrs.id')) :
                $this->set('required.tagged.attrs.id', "Form{$this->form()->index()}-fieldTag--{$this->getSlug()}");
            endif;
            if (!$this->get('required.tagged.attrs.id')) :
                $this->pull('required.tagged.attrs.id');
            endif;

            $default_class = "Form-fieldTag Form-fieldTag--{$this->getType()} Form-fieldTag--{$this->getSlug()}";
            if (!$this->has('required.tagged.attrs.class')) :
                $this->set('required.tagged.attrs.class', $default_class);
            else :
                $this->set(
                    'required.tagged.attrs.class',
                    sprintf($this->get('required.tagged.attrs.class', ''), $default_class)
                );
            endif;
            if (!$this->get('required.tagged.attrs.class')) :
                $this->pull('required.tagged.attrs.class');
            endif;
        endif;
    }

    /**
     * @inheritdoc
     */
    public function setExtra($key, $value)
    {
        return $this->set("extras.{$key}", $value);
    }

    /**
     * @inheritDoc
     */
    public function setOnError(bool $status = true): FactoryField
    {
       $this->error = $status;

       return $this;
    }

    /**
     * @inheritdoc
     */
    public function setPosition($position = 0)
    {
        $this->set('position', $position);

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function setValue($value)
    {
        $this->events('field.set.value', [&$value, $this]);

        $this->set('value', $value);
    }

    /**
     * @inheritdoc
     */
    public function supports($support = null)
    {
        if (is_null($support)) :
            return $this->get('supports', []);
        else :
            return in_array($support, $this->get('supports', []));
        endif;
    }
}