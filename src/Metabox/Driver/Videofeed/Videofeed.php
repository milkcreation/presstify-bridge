<?php declare(strict_types=1);

namespace tiFy\Metabox\Driver\Videofeed;

use tiFy\Contracts\Metabox\MetaboxDriver as MetaboxDriverContract;
use tiFy\Contracts\Routing\Route;
use tiFy\Metabox\MetaboxDriver;
use tiFy\Support\Proxy\{Request, Router};

class Videofeed extends MetaboxDriver
{
    /**
     * Indice de l'instance courante.
     * @var integer
     */
    static $instance = 0;

    /**
     * Alias de qualification.
     * @var string
     */
    protected $alias = 'videofeed';

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
    public function defaultParams(): array
    {
        return [
            'classes'   => [],
            'max'       => -1,
            'library'   => true,
            'removable' => true,
            'sortable'  => true,
        ];
    }

    /**
     * @inheritDoc
     */
    public function defaults(): array
    {
        return array_merge(parent::defaults(), [
            'name'  => 'videofeed',
            'title' => __('Vidéos', 'tify'),
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
     * @param array $value Attributs de configuration de la vidéo.
     *
     * @return array
     */
    public function item($index, array $value = []): array
    {
        $name = $this->get('name');
        $index = !is_numeric($index) ? $index : uniqid();

        $value = array_merge([
            'poster' => '',
            'src'    => '',
        ], $value);

        $value['poster'] = ($img = wp_get_attachment_image_src($value['poster'], 'thumbnail'))
            ? $img[0]
            : $value['poster'];

        return [
            'index' => $index,
            'name'  => $this->get('params.max', -1) === 1 ? "{$name}[]" : "{$name}[{$index}]",
            'value' => $value,
        ];
    }

    /**
     * @inheritDoc
     */
    public function parse(): MetaboxDriverContract
    {
        parent::parse();

        $defaultClasses = [
            'addnew'  => 'MetaboxVideofeed-addnew ThemeButton--primary ThemeButton--normal',
            'down'    => 'MetaboxVideofeed-itemSortDown ThemeFeed-itemSortDown',
            'input'   => 'MetaboxVideofeed-itemInput',
            'item'    => 'MetaboxVideofeed-item ThemeFeed-item',
            'items'   => 'MetaboxVideofeed-items ThemeFeed-items',
            'library' => 'MetaboxVideofeed-itemLibrary ThemeButton--secondary ThemeButton--small',
            'order'   => 'MetaboxVideofeed-itemOrder ThemeFeed-itemOrder',
            'remove'  => 'MetaboxVideofeed-itemRemove ThemeFeed-itemRemove',
            'sort'    => 'MetaboxVideofeed-itemSortHandle ThemeFeed-itemSortHandle',
            'up'      => 'MetaboxVideofeed-itemSortUp ThemeFeed-itemSortUp',
        ];
        foreach ($defaultClasses as $k => $v) {
            $this->params(["classes.{$k}" => sprintf($this->get("classes.{$k}", '%s'), $v)]);
        }

        $this->params([
            'attrs.class'        => sprintf($this->get('attrs.class', '%s'), 'MetaboxVideofeed'),
            'attrs.data-control' => 'metabox-videofeed',
        ]);

        if ($sortable = $this->get('sortable')) {
            $this->params([
                'sortable' => array_merge([
                    'placeholder' => 'MetaboxVideofeed-itemPlaceholder',
                    'axis'        => 'y',
                ], is_array($sortable) ? $sortable : []),
            ]);
        }

        $this->params([
            'attrs.data-options' => [
                'ajax'      => [
                    'data'     => [
                        'max'    => $this->params('max', -1),
                        'name'   => $this->get('name'),
                        'viewer' => $this->get('viewer', []),
                    ],
                    'dataType' => 'json',
                    'method'   => 'post',
                    'url'      => $this->getUrl(),
                ],
                'classes'   => $this->params('classes', []),
                'library'   => $this->params('library'),
                'removable' => $this->params('removable'),
                'sortable'  => $this->params('sortable'),
            ],
        ]);

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function render(): string
    {
        if ($values = $this->value()) {
            $items = [];
            array_walk($values, function ($value, $index) use (&$items) {
                $items[] = $this->item($index, $value);
            });
            $this->set('items', $items);
        }

        return parent::render();
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

        if (($max > 0) && ($index >= $max)) {
            return [
                'success' => false,
                'data'    => __('Nombre maximum de vidéos atteint.', 'tify'),
            ];
        } else {
            $this->set([
                'name'   => Request::input('name', []),
                'params' => [
                    'max' => $max,
                ],
                'viewer' => Request::input('viewer', []),
            ]);

            return [
                'success' => true,
                'data'    => (string)$this->viewer('item-wrap', $this->item($index)),
            ];
        }
    }
}