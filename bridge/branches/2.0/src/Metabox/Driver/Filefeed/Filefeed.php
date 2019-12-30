<?php declare(strict_types=1);

namespace tiFy\Metabox\Driver\Filefeed;

use tiFy\Contracts\Metabox\MetaboxDriver as MetaboxDriverContract;
use tiFy\Contracts\Routing\Route;
use tiFy\Metabox\MetaboxDriver;
use tiFy\Support\Proxy\{Request, Router};

class Filefeed extends MetaboxDriver
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
    protected $alias = 'filefeed';

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
            'filetype'  => '', // video || application/pdf || video/flv, video/mp4,
            'max'       => -1,
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
            'name'  => 'filefeed',
            'title' => __('Partage de fichier', 'tify'),
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
     * @param int|null $value Identifiant de qualification du média.
     *
     * @return array
     */
    public function item($index, ?int $value = null): array
    {
        $name = $this->get('name');
        $index = !is_numeric($index) ? $index : uniqid();

        return [
            'name'  => $this->get('params.max', -1) === 1 ? "{$name}[]" : "{$name}[{$index}]",
            'value' => $value,
            'index' => $index,
            'icon'  => wp_get_attachment_image($value, [48, 64], true),
            'title' => get_the_title($value),
            'mime'  => get_post_mime_type($value),
        ];
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
     * @inheritDoc
     */
    public function parse(): MetaboxDriverContract
    {
        parent::parse();

        $defaultClasses = [
            'addnew' => 'MetaboxFilefeed-addnew ThemeButton--primary ThemeButton--normal',
            'down'   => 'MetaboxFilefeed-itemSortDown ThemeFeed-itemSortDown',
            'input'  => 'MetaboxFilefeed-itemInput',
            'item'   => 'MetaboxFilefeed-item ThemeFeed-item',
            'items'  => 'MetaboxFilefeed-items ThemeFeed-items',
            'order'  => 'MetaboxFilefeed-itemOrder ThemeFeed-itemOrder',
            'remove' => 'MetaboxFilefeed-itemRemove ThemeFeed-itemRemove',
            'sort'   => 'MetaboxFilefeed-itemSortHandle ThemeFeed-itemSortHandle',
            'up'     => 'MetaboxFilefeed-itemSortUp ThemeFeed-itemSortUp',
        ];
        foreach ($defaultClasses as $k => $v) {
            $this->params(["classes.{$k}" => sprintf($this->get("classes.{$k}", '%s'), $v)]);
        }

        $this->params([
            'attrs.class'        => sprintf($this->get('attrs.class', '%s'), 'MetaboxFilefeed'),
            'attrs.data-control' => 'metabox-filefeed',
        ]);

        if ($sortable = $this->get('sortable')) {
            $this->params([
                'sortable' => array_merge([
                    'placeholder' => 'MetaboxFilefeed-itemPlaceholder',
                    'axis'        => 'y',
                ], is_array($sortable) ? $sortable : []),
            ]);
        }

        $this->params([
            'attrs.data-options' => [
                'ajax'      => array_merge([
                    'data'   => [
                        'max'    => $this->params('max', -1),
                        'name'   => $this->get('name'),
                        'viewer' => $this->get('viewer', []),
                    ],
                    'method' => 'post',
                    'url'    => $this->getUrl(),
                ]),
                'classes'   => $this->params('classes', []),
                'media'     => [
                    'multiple' => ($this->params('max', -1) === 1 ? false : true),
                    'library'  => [
                        'type' => $this->params('filetype'),
                    ],
                ],
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
                $items[] = $this->item($index, (int)$value);
            });
            $this->set('items', $items);
        }

        return parent::render();
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
        $value = (int)Request::input('value');

        if (($max > 0) && ($index >= $max)) {
            return [
                'success' => false,
                'data'    => __('Nombre maximum de fichiers partagés atteint.', 'tify'),
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
                'data'    => (string)$this->viewer('item-wrap', $this->item($index, $value)),
            ];
        }
    }
}