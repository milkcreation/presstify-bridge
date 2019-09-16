<?php declare(strict_types=1);

namespace tiFy\Field\Fields\Suggest;

use tiFy\Contracts\Field\FieldFactory as FieldFactoryContract;
use tiFy\Contracts\Field\Suggest as SuggestContract;
use tiFy\Field\FieldFactory;
use tiFy\Support\Proxy\{Request as req, Router as route};

class Suggest extends FieldFactory implements SuggestContract
{
    /**
     * Jeu de données d'exemple.
     * @var string[]
     */
    protected $languages = [
        "ActionScript",
        "AppleScript",
        "Asp",
        "BASIC",
        "C",
        "C++",
        "Clojure",
        "COBOL",
        "ColdFusion",
        "Erlang",
        "Fortran",
        "Groovy",
        "Haskell",
        "Java",
        "JavaScript",
        "Lisp",
        "Perl",
        "PHP",
        "Python",
        "Ruby",
        "Scala",
        "Scheme",
    ];

    /**
     * Url de traitement.
     * @var string Url de traitement
     */
    protected $url;

    /**
     * @inheritDoc
     */
    public function boot(): void
    {
        $this->setUrl();
    }

    /**
     * {@inheritDoc}
     *
     * @return array {
     *      @var array $attrs Attributs HTML du champ.
     *      @var string $after Contenu placé après le champ.
     *      @var string $before Contenu placé avant le champ.
     *      @var string $name Clé d'indice de la valeur de soumission du champ.
     *      @var string $value Valeur courante de soumission du champ.
     *      @var array $viewer Liste des attributs de configuration du pilote d'affichage.
     *      @var array|bool $ajax Liste des attributs de recherche des éléments via une requête xhr.
     *      @var bool|array $alt Activation du champ alternatif de stockage du résultat de la recherche|attributs de
     *      configuration du champ altérnatif. @see \tiFy\Field\Fields\Hidden\Hidden
     *      @see https://api.jquery.com/jquery.ajax/
     *      @var array $options Liste des attributs de configuration de l'autocomplétion.
     *      @see https://api.jqueryui.com/autocomplete/
     * }
     */
    public function defaults(): array
    {
        return [
            'attrs'     => [],
            'after'     => '',
            'before'    => '',
            'name'      => '',
            'value'     => '',
            'viewer'    => [],
            'ajax'      => false,
            'alt'       => false,
            'container' => [],
            'options'   => [
                'minLength' => 2,
            ],
            'spinner'   => true,
        ];
    }

    /**
     * @inheritDoc
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * @inheritDoc
     */
    public function parse(): FieldFactoryContract
    {
        parent::parse();

        $this->set('options.classes', array_merge([
            'picker'      => 'FieldSuggest-picker',
            'picker-item' => 'FieldSuggest-pickerItem',
        ], $this->get('options.classes', [])));

        if ($alt = $this->get('alt')) {
            $this->set('alt', array_merge([
                'attrs' => [
                    'class' => '%s FieldSuggest-alt'
                ]
            ],is_array($alt) ? $alt : []));
            $this->set('alt.attrs.data-control', 'suggest.alt');
        }

        if ($spinner = $this->get('spinner')) {
            $this->set('spinner', array_merge([
                'tag' => 'div',
                'attrs' => [
                    'class' => '%s ThemeSpinner FieldSuggest-spinner'
                ],
            ],is_array($spinner) ? $spinner : []));
            $this->set('spinner.attrs.data-control', 'suggest.spinner');
        }

        $options = [
            'autocomplete' => $this->get('options', []),
        ];

        if ($ajax = $this->get('ajax')) {
            $defaults = [
                'url'  => $this->getUrl(),
                'type' => 'post',
                'data' => [],
            ];
            $options['ajax'] = is_array($ajax) ? array_merge($defaults, $ajax) : $defaults;
        } elseif (!$this->has('autocomplete.source')) {
            $options['autocomplete']['source'] = $this->languages;
        }

        $this->set('attrs.data-control', 'suggest.input');

        $container_class = 'FieldSuggest FieldSuggest--' . $this->getIndex();
        if (!$this->has('container.attrs.class')) {
            $this->set('container.attrs.class', $container_class);
        } else {
            $this->set('container.attrs.class', sprintf($this->get('container.attrs.class', ''), $container_class));
        }

        $this->set('container', array_merge([
            'tag'     => 'span',
        ], $this->get('container', [])));

        $this->set([
            'container.attrs.data-control' => 'suggest',
            'container.attrs.data-options' => $options,
        ]);

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function parseDefaults(): FieldFactoryContract
    {
        $default_class = 'FieldSuggest-input FieldSuggest-input' . '--' . $this->getIndex();
        if (!$this->has('attrs.class')) {
            $this->set('attrs.class', $default_class);
        } else {
            $this->set('attrs.class', sprintf($this->get('attrs.class', ''), $default_class));
        }

        $this->parseName();
        $this->parseValue();
        $this->parseViewer();

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setUrl(?string $url =  null): FieldFactoryContract
    {
        $this->url = is_null($url) ? route::xhr(md5($this->getAlias()), [$this, 'xhrResponse'])->getUrl() : $url;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function xhrResponse(...$args): array
    {
        $items = collect($this->languages)
            ->filter(function ($label) {
                return preg_match('/' . req::input('_term', '') . '/i', $label);
            })->map(function (&$label, $value) {
                return [
                    'alt'    => (string)$value,
                    'label'  => (string)$this->viewer('label', compact('label', 'value')),
                    'value'  => (string)$value
                ];
            })->all();

        return [
            'success' => true,
            'data'    => [
                'items' => $items,
            ],
        ];
    }
}