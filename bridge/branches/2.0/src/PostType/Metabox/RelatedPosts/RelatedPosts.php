<?php declare(strict_types=1);

namespace tiFy\PostType\Metabox\RelatedPosts;

use tiFy\Contracts\Metabox\MetaboxWpPostController as MetaboxWpPostControllerContract;
use tiFy\Metabox\MetaboxWpPostController;
use tiFy\Support\Proxy\{Request as req, Router as route};
use tiFy\Wordpress\Query\QueryPost;
use WP_Post;

class RelatedPosts extends MetaboxWpPostController
{
    /**
     * Indice de l'intance courante.
     * @var integer
     */
    static $instance = 0;

    /**
     * Liste des éléments.
     * @var array
     */
    protected $items = [];

    /**
     * Ordre des éléments.
     * @var int
     */
    protected $order = 0;

    /**
     * Url de traitement Xhr.
     * @var string Url de traitement
     */
    protected $url = '';

    /**
     * @inheritDoc
     */
    public function boot()
    {
        static::$instance++;
        $this->setUrl();
    }

    /**
     * @inheritDoc
     *
     * @param WP_Post $post
     */
    public function content($post = null, $args = null, $null = null): string
    {
        $items = [];
        if ($ids = get_post_meta($post->ID, $this->get('name'), true) ?: []) {
            foreach ($ids as $id) {
                if ($item = QueryPost::createFromId($id)) {
                    $items[] = QueryPost::createFromId((int)$id);
                }
            }
        }

        $this->set([
            'attrs'   => [
                'id'           => 'MetaboxRelatedPosts--' . static::$instance,
                'class'        => 'MetaboxRelatedPosts MetaboxRelatedPosts--' . $this->get('name'),
                'data-control' => 'metabox.related-posts',
            ],
            'index'   => static::$instance,
            'items'   => $items,
            'name'    => $this->get('name'),
            'suggest' => [
                'ajax'      => [
                    'data' => [
                        'query_args' => array_merge($this->get('query_args'), [
                            'post_type'      => $this->get('post_type', 'any'),
                            'post_status'    => $this->get('post_status', 'publish'),
                            'posts_per_page' => -1,
                        ]),
                    ],
                ],
                'alt'       => true,
                'attrs'     => [
                    'class'       => 'MetaboxRelatedPosts-suggestInput',
                    'placeholder' => $this->get('placeholder'),
                ]
            ],
        ]);

        $this->set('attrs.data-options', [
            'ajax'     => [
                'data' => [
                    'name'       => $this->get('name'),
                ],
                'dataType' => 'json',
                'type'     => 'post',
                'url'      => $this->getUrl(),
            ],
            'sortable' => $this->get('sortable', []),
        ]);

        return (string)$this->viewer('content', $this->all());
    }

    /**
     * @inheritDoc
     */
    public function defaults(): array
    {
        return [
            'name'        => '_tify_taboox_related_posts',
            'post_type'   => 'any',
            'post_status' => 'publish',
            'query_args'  => [],
            'elements'    => ['title', 'ico'],
            'placeholder' => __('Rechercher un contenu en relation', 'tify'),
            'max'         => -1,
        ];
    }

    /**
     * Récupération de l'url de traitement Xhr.
     *
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * Définition de l'url de traitement Xhr.
     *
     * @param string|null $url
     *
     * @return $this
     */
    public function setUrl(?string $url = null): MetaboxWpPostControllerContract
    {
        $this->url = is_null($url)
            ? route::xhr(md5('MetaboxRelatedPosts--' . static::$instance), [$this, 'xhrResponse'])
                ->getUrl()
            : $url;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function header($post = null, $args = null, $null = null): string
    {
        return $this->item->getTitle() ?: __('Éléments en relation', 'tify');
    }

    /**
     * @inheritDoc
     */
    public function metadatas(): array
    {
        return [$this->get('name')];
    }

    /**
     * Récupération d'un élément via Ajax
     *
     * @param array ...$args Liste des arguments de requête passés dans l'url.
     *
     * @return array
     */
    public function xhrResponse(...$args): array
    {
        if ($item = QueryPost::createFromId(req::input('post_id'))) {
            return [
                'success' => true,
                'data'    => (string)$this->viewer('item', [
                    'index' => req::input('index', 0),
                    'item'  => $item,
                    'name'  => req::input('name', ''),
                ]),
            ];
        } else {
            return [
                'success' => false,
                'data'    => __('Impossible de récupérer le contenu associé', 'tify'),
            ];
        }

    }
}