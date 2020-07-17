<?php declare(strict_types=1);

namespace tiFy\Wordpress\Field\Driver\Findposts;

use tiFy\Contracts\Field\FieldDriver as BaseFieldDriverContract;
use tiFy\Contracts\Routing\Route;
use tiFy\Wordpress\Contracts\Field\{Findposts as FindpostsContract, FieldDriver as FieldDriverContract};
use tiFy\Wordpress\Field\FieldDriver;
use tiFy\Support\Proxy\{Asset, Request, Router};
use WP_Post, WP_Query;

class Findposts extends FieldDriver implements FindpostsContract
{
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
        parent::boot();

        $this->setUrl();
    }

    /**
     * {@inheritDoc}
     *
     * @return array $attributes {
     * @var string $before Contenu placé avant le champ.
     * @var string $after Contenu placé après le champ.
     * @var string $name Clé d'indice de la valeur de soumission du champ.
     * @var string $value Valeur courante de soumission du champ.
     * @var array $attrs Attributs HTML du champ.
     * @var array $viewer Liste des attributs de configuration du controleur de gabarit d'affichage.
     * @var array $query_args
     * }
     */
    public function defaults(): array
    {
        return [
            'before'     => '',
            'after'      => '',
            'name'       => '',
            'value'      => '',
            'attrs'      => [],
            'viewer'     => [],
            'query_args' => [],
        ];
    }

    /**
     * @inheritDoc
     */
    public function getUrl(...$params): string
    {
        return $this->url instanceof Route ? (string)$this->url->getUrl($params) : $this->url;
    }

    /**
     * @inheritDoc
     */
    public function parse(): BaseFieldDriverContract
    {
        parent::parse();

        $uniqid = md5(uniqid() . $this->getIndex());

        if (!$post_types = $this->pull('query_args.post_type')) {
            $post_types = get_post_types(['public' => true], 'names');
            $post_types = array_values($post_types);
        }

        $available_post_types = [];
        foreach ($post_types as $pt) {
            if ($obj = get_post_type_object($pt)) {
                $available_post_types[$pt] = $obj->label;
            }
        }

        if (count($available_post_types) > 1) {
            $available_post_types = array_merge(['any' => __('Tous', 'tify')], $available_post_types);
        }

        $this->set([
            'uniqid'               => $uniqid,
            'attrs.data-control'   => 'findposts',
            'attrs.data-options'   => [
                'ajax'   => [
                    'url'      => $this->getUrl(),
                    'dataType' => 'json',
                    'method'   => 'POST',
                ],
                'uniqid' => $uniqid,
            ],
            'attrs.id'             => "FieldFindposts--{$uniqid}",
            'available_post_types' => $available_post_types,
            'modal.attrs'          => [
                'id'           => "FieldFindposts-modal--{$uniqid}",
                'class'        => "find-box FieldFindposts-modal",
                'data-control' => 'findposts.modal',
                'style'        => 'display: none;',
            ],
            'tmpl.attrs'           => [
                'id'           => "FieldFindposts-response--{$uniqid}",
                'data-control' => 'findposts.tmpl',
            ],
            'post_types'           => $post_types,
        ]);

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function render(): string
    {
        Asset::setDataJs($this->getAlias() . 'l10n', [
            'error' => __('Une erreur s\'est produite. Veuillez recharger la page et essayer à nouveau.', 'tify'),
        ], true);

        return parent::render();
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
        if (!wp_verify_nonce(Request::post('_ajax_nonce'), 'Findposts')) {
            return [
                'success' => false,
                'data'    => __('Invalid nonce'),
            ];
        }

        $query_args = wp_parse_args([
            'post_type'      => Request::post('post_type', 'any'),
            'post_status'    => 'any',
            'posts_per_page' => 50,
        ], Request::input('query_args', []));

        $s = wp_unslash(Request::input('ps', ''));
        if ('' !== $s) {
            $query_args['s'] = $s;
        }

        /** @var WP_Post[]|array $post */
        $results = (new WP_Query())->query($query_args);

        if (empty($results)) {
            return [
                'success' => false,
                'data'    => __('No items found.'),
            ];
        } else {
            $posts = [];

            foreach ($results as $i => $r) {
                $posts[] = [
                    'ID'          => $r->ID,
                    'post_title'  => trim($r->post_title) ?: __('(no title)'),
                    'post_type'   => ($type = get_post_type_object($r->post_type))
                        ? $type->labels->singular_name : '--',
                    'post_status' => ($st = get_post_status_object($r->post_status)) ? $st->label : '--',
                    'post_date'   => ('0000-00-00 00:00:00' !== $r->post_date)
                        ? mysql2date(__('Y/m/d'), $r->post_date) : '--',
                    'alt'         => ($i % 2 !== 0) ? 'alternate' : '',
                    'value'       => get_permalink($r->ID),
                ];
            }

            return [
                'success' => true,
                'data'    => $posts,
            ];
        }
    }
}