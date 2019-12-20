<?php declare(strict_types=1);

namespace tiFy\Metabox\Drivers\Slidefeed;

use tiFy\Contracts\Metabox\MetaboxDriver as MetaboxDriverContract;
use tiFy\Contracts\Routing\Route;
use tiFy\Metabox\MetaboxDriver;
use tiFy\Support\Proxy\{Request, Router};

class Slidefeed extends MetaboxDriver
{
    /**
     * Indice de l'intance courante.
     * @var integer
     */
    static $instance = 0;

    /**
     * Alias de qualification.
     * @var string
     */
    protected $alias = 'slidefeed';

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
        static::$instance++;
        $this->setUrl();
    }

    /**
     * @inheritDoc
     */
    public function content(): string
    {
        if ($values = $this->value()) {
            $items = [];
            array_walk($values, function ($value, $index) use (&$items) {
                $items[] = $this->item($index, $value);
            });
            $this->set('items', $items);
        }

        return parent::content();
    }

    /**
     * @inheritDoc
     */
    public function defaultParams(): array
    {
        return [
            'addnew'  => true,
            'classes' => [],
            'fields'  => ['image', 'title', 'url', 'caption'],
            'max'     => -1,
            'suggest' => false,
        ];
    }

    /**
     * @inheritDoc
     */
    public function defaults(): array
    {
        return array_merge(parent::defaults(), [
            'name'  => 'slidefeed',
            'title' => __('Diaporama', 'tify'),
        ]);
    }

    /**
     * Récupération de l'url de traitement de la requête XHR.
     *
     * @param array ...$params Liste des paramètres optionnels de formatage de l'url.
     *
     * @return string
     */
    public function getUrl(...$params): string
    {
        return $this->url instanceof Route ? $this->url->getUrl($params) : $this->url;
    }

    /**
     * Définition d'un élément.
     *
     * @param int|string $index Indice de l'élément.
     * @param array $value Données.
     *
     * @return array
     */
    public function item($index, $value): array
    {
        $name = $this->get('name');
        $index = !is_numeric($index) ? $index : uniqid();

        return [
            'fields' => $this->get('params.fields', []),
            'index'  => $index,
            'name'   => $this->get('params.max', -1) === 1 ? "{$name}[]" : "{$name}[{$index}]",
            'value'  => $value,
        ];
    }

    /**
     * @inheritDoc
     */
    public function parse(): MetaboxDriverContract
    {
        parent::parse();

        $defaultClasses = [
            'addnew'  => 'MetaboxSlidefeed-addnew ThemeButton--primary ThemeButton--normal',
            'down'    => 'MetaboxSlidefeed-itemSortDown ThemeFeed-itemSortDown',
            'item'    => 'MetaboxSlidefeed-item ThemeFeed-item',
            'items'   => 'MetaboxSlidefeed-items ThemeFeed-items',
            'order'   => 'MetaboxSlidefeed-itemOrder ThemeFeed-itemOrder',
            'remove'  => 'MetaboxSlidefeed-itemRemove ThemeFeed-itemRemove',
            'sort'    => 'MetaboxSlidefeed-itemSortHandle ThemeFeed-itemSortHandle',
            'suggest' => 'MetaboxSlidefeed-suggest',
            'up'      => 'MetaboxSlidefeed-itemSortUp ThemeFeed-itemSortUp',
        ];
        foreach ($defaultClasses as $k => $v) {
            $this->params(["classes.{$k}" => sprintf($this->get("classes.{$k}", '%s'), $v)]);
        }

        if ($suggest = $this->params('suggest', true)) {
            $defaultSuggest = [
                'ajax'    => true,
                'attrs'   => [
                    'data-control' => 'metabox-slidefeed.suggest',
                    'placeholder'  => __('Rechercher parmi les contenus du site', 'tify'),
                ],
                'classes' => [
                    'wrap' => '%s MetaboxSlidefeed-suggestWrap',
                ],
            ];
            $this->params(['suggest' => is_array($suggest) ? array_merge($defaultSuggest, $suggest) : $defaultSuggest]);
        }

        $this->params([
            'addnew'             => [
                'attrs'   => [
                    'data-control' => 'metabox-slidefeed.addnew',
                ],
                'content' => __('Ajouter une vignette', 'tify'),
            ],
            'attrs.class'        => sprintf($this->get('attrs.class', '%s'), 'MetaboxSlidefeed'),
            'attrs.data-control' => 'metabox-slidefeed',
            'attrs.data-options' => [
                'ajax'    => [
                    'data'     => [
                        'fields' => $this->params('fields', []),
                        'max'    => $this->params('max', -1),
                        'name'   => $this->get('name'),
                        'viewer' => $this->get('viewer', []),
                    ],
                    'dataType' => 'json',
                    'method'   => 'post',
                    'url'      => $this->getUrl(),
                ],
                'classes' => $this->params('classes', []),
                'suggest' => $this->params('suggest'),
            ],
        ]);

        return $this;
    }

    /**
     * Définition de l'url de traitement XHR.
     *
     * @param string|null $url
     *
     * @return $this
     */
    public function setUrl(?string $url = null): self
    {
        $this->url = is_null($url) ? Router::xhr(md5($this->alias . static::$instance), [$this, 'xhrResponse']) : $url;

        return $this;
    }

    /**
     * Controleur de traitement de la requête XHR.
     *
     * @param array ...$args Liste des arguments de requête passés dans l'url.
     *
     * @return array
     */
    public function xhrResponse(...$args): array
    {
        $index = Request::input('index');
        $max = Request::input('max', 0);
        $value = Request::input('value', '');

        if (($max > 0) && ($index >= $max)) {
            return [
                'success' => false,
                'data'    => __('Nombre maximum de vignette atteint.', 'tify'),
            ];
        } else {
            $this->set([
                'name'   => Request::input('name', []),
                'params' => [
                    'fields' => Request::input('fields', []),
                    'max'    => $max,
                ],
                'viewer' => Request::input('viewer', []),
            ]);

            return [
                'success' => true,
                'data'    => (string)$this->viewer('item-wrap', $this->item($index, $value)),
            ];
        }
    }
}