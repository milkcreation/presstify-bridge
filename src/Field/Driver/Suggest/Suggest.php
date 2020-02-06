<?php declare(strict_types=1);

namespace tiFy\Field\Driver\Suggest;

use tiFy\Contracts\Field\FieldDriver as FieldDriverContract;
use tiFy\Contracts\Field\Suggest as SuggestContract;
use tiFy\Contracts\Routing\Route;
use tiFy\Field\FieldDriver;
use tiFy\Support\Proxy\{Request, Router};

class Suggest extends FieldDriver implements SuggestContract
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
     * Url de traitement de requête XHR.
     * @var Route|string
     */
    protected $url = '';

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
     *      configuration du champ altérnatif. @see \tiFy\Field\Driver\Hidden\Hidden
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
            'classes'   => [],
            'options'   => [
                'minLength' => 2,
            ],
            'spinner'   => true,
            'reset'     => true
        ];
    }

    /**
     * @inheritDoc
     */
    public function getUrl(...$params): string
    {
        return $this->url instanceof Route ? $this->url->getUrl($params) : $this->url;
    }

    /**
     * @inheritDoc
     */
    public function parse(): FieldDriverContract
    {
        parent::parse();

        $defaultClasses = [
            'alt'     => 'FieldSuggest-alt',
            'input'   => 'FieldSuggest-input',
            'item'    => 'FieldSuggest-pickerItem',
            'items'   => 'FieldSuggest-picker',
            'reset'   => 'FieldSuggest-reset ThemeButton--close',
            'spinner' => 'FieldSuggest-spinner ThemeSpinner',
            'wrap'    => 'FieldSuggest-wrap',
        ];
        foreach ($defaultClasses as $k => $v) {
            $this->set("classes.{$k}", sprintf($this->get("classes.{$k}", '%s'), $v));
        }

        $options = [
            'alt'          => $this->get('alt'),
            'autocomplete' => $this->get('options', []),
            'classes'      => $this->get('classes', []),
            'reset'        => $this->get('reset'),
            'spinner'      => $this->get('spinner'),
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

        $this->set([
            'attrs.data-control'           => $this->get('attrs.data-control', 'suggest'),
            'attrs.data-options'           => $options
        ]);

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setUrl(?string $url = null): FieldDriverContract
    {
        $this->url = is_null($url) ? Router::xhr(md5($this->getAlias()), [$this, 'xhrResponse']) : $url;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function xhrResponse(...$args): array
    {
        $items = collect($this->languages)
            ->filter(function ($label) {
                return preg_match('/' . Request::input('_term', '') . '/i', $label);
            })->map(function (&$label, $value) {
                return [
                    'alt'   => (string)$value,
                    'label' => (string)$this->viewer('item-label', compact('label', 'value')),
                    'value' => (string)$value,
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