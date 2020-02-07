<?php declare(strict_types=1);

namespace tiFy\Wordpress\Field\Driver\Findposts;

use tiFy\Contracts\Field\FieldDriver as BaseFieldDriverContract;
use tiFy\Wordpress\Contracts\Field\Findposts as FindpostsContract;
use tiFy\Wordpress\Field\FieldDriver;
use tiFy\Support\Proxy\Asset;
use WP_Post;
use WP_Query;

class Findposts extends FieldDriver implements FindpostsContract
{
    /**
     * @inheritDoc
     */
    public function boot(): void
    {
        parent::boot();

        add_action('wp_ajax_field_findposts', [$this, 'xhrResponse']);
        add_action('wp_ajax_nopriv_field_findposts', [$this, 'xhrResponse']);

        add_action('wp_ajax_field_findposts_post_permalink', [$this, 'xhrGetPermalink']);
        add_action('wp_ajax_nopriv_field_findposts_post_permalink', [$this, 'xhrGetPermalink']);
    }

    /**
     * {@inheritDoc}
     *
     * @return array $attributes {
     *      @var string $before Contenu placé avant le champ.
     *      @var string $after Contenu placé après le champ.
     *      @var string $name Clé d'indice de la valeur de soumission du champ.
     *      @var string $value Valeur courante de soumission du champ.
     *      @var array $attrs Attributs HTML du champ.
     *      @var array $viewer Liste des attributs de configuration du controleur de gabarit d'affichage.
     *      @var string $ajax_action
     *      @var array $query_args
     *      @var array $viewer Liste des attributs de configuration de la classe des gabarits d'affichage.
     * }
     */
    public function defaults(): array
    {
        return [
            'before'      => '',
            'after'       => '',
            'name'        => '',
            'value'       => '',
            'attrs'       => [],
            'viewer'      => [],
            'ajax_action' => 'field_findposts',
            'query_args'  => [],
        ];
    }

    /**
     * @inheritDoc
     */
    public function parse(): BaseFieldDriverContract
    {
        parent::parse();

        $this->set('attrs.id', 'FieldFindposts--' . $this->getId());

        return $this;
    }

    /**
     * Affichage de la fenêtre modale.
     *
     * @param string $found_action Action Ajax de récupération des éléments.
     * @param array $query_args Arguments de la requête de récupération des éléments.
     *
     * @todo pagination + gestion instance multiple
     *
     * @return string
     */
    public function modal($found_action = '', $query_args = [])
    {
        // Définition des types de post
        if (!empty($query_args['post_type'])) {
            $post_types = (array)$query_args['post_type'];
            unset($query_args['post_type']);
        } else {
            $post_types = get_post_types(['public' => true], 'objects');
            unset($post_types['attachment']);
            $post_types = array_keys($post_types);
        }

        return (string)$this->viewer('modal', compact('found_action', 'query_args', 'post_types'));
    }

    /**
     * @inheritDoc
     */
    public function render(): string
    {
        static $init;

        if (!$init++) {
            add_action('admin_footer', function () {
                echo $this->modal($this->get('ajax_action'), $this->get('query_args'));
            });
        }

        Asset::setDataJs($this->getAlias() . 'l10n', [
            'error' => __( 'Une erreur s\'est produite. Veuillez recharger la page et essayer à nouveau.', 'tify')
        ], true);

        return parent::render();
    }

    /**
     * Récupération d'un permalien de post selon son ID.
     *
     * @return void
     */
    public function xhrGetPermalink(): void
    {
        // Traitement des arguments de requête
        $post_id = intval(request()->post('post_id', 0));
        $relative = request()->post('relative', false);
        $default = request()->post('default', site_url('/'));

        // Traitement du permalien
        $permalink = ($_permalink = get_permalink($post_id)) ? $_permalink : $default;
        if ($relative) {
            $url_path = parse_url(site_url('/'), PHP_URL_PATH);
            $permalink = $url_path . preg_replace('/' . preg_quote(site_url('/'), '/') . '/', '', $permalink);
        }

        wp_die($permalink);
    }

    /**
     * Récupération de la reponse via Ajax
     *
     * @return void
     */
    public function xhrResponse(): void
    {
        check_ajax_referer('FieldFindposts' . request()->input('id'));

        $post_types = get_post_types(['public' => true], 'objects');

        $s = wp_unslash(request()->input('ps', ''));
        $args = [
            'post_type'      => array_keys($post_types),
            'post_status'    => 'any',
            'posts_per_page' => 50,
        ];

        $args = wp_parse_args(request()->input('query_args', []), $args);
        array_diff($args['post_type'], ['attachment']);

        if ('' !== $s) {
            $args['s'] = $s;
        }

        $posts = (new WP_Query())->query($args);

        if (!$posts) {
            wp_send_json_error(__('No items found.'));
        } else {
            $alt = 'alternate';

            foreach ($posts as &$post) {
                /** @var WP_Post $post */
                $post = $post->to_array();

                $post['_post_title'] = trim($post['post_title']) ? $post['post_title'] : __('(no title)');

                switch ($post['post_status']) {
                    case 'publish' :
                    case 'private' :
                        $post['_post_status'] = __('Published');
                        break;
                    case 'future' :
                        $post['_post_status'] = __('Scheduled');
                        break;
                    case 'pending' :
                        $post['_post_status'] = __('Pending Review');
                        break;
                    case 'draft' :
                        $post['_post_status'] = __('Draft');
                        break;
                    default:
                        $post['_post_status'] = '';
                        break;
                }

                $post['_post_date'] = ('0000-00-00 00:00:00' == $post['post_date']) ? '' : mysql2date(__('Y/m/d'),
                    $post['post_date']);
            }
            wp_send_json_success($this->viewer('response', compact('post_types', 'posts', 'alt')));
        }
    }
}