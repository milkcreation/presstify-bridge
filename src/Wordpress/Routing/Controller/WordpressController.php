<?php declare(strict_types=1);

namespace tiFy\Wordpress\Routing\Controller;

use tiFy\Contracts\Http\Response as ResponseContract;
use tiFy\Http\Response;
use tiFy\Routing\BaseController;
use tiFy\Support\Str;

class WordpressController extends BaseController
{
    /**
     * Cartographie des méthodes de récupération des gabarits d'affichage
     * @see ./wp-includes/template-loader.php
     *
     * @var array
     */
    protected $tagTemplates = [
        'embed'             => 'get_embed_template',
        '404'               => 'get_404_template',
        'search'            => 'get_search_template',
        'front_page'        => 'get_front_page_template',
        'home'              => 'get_home_template',
        'privacy_policy'    => 'get_privacy_policy_template',
        'post_type_archive' => 'get_post_type_archive_template',
        'tax'               => 'get_taxonomy_template',
        'attachment'        => 'get_attachment_template',
        'single'            => 'get_single_template',
        'page'              => 'get_page_template',
        'singular'          => 'get_singular_template',
        'category'          => 'get_category_template',
        'tag'               => 'get_tag_template',
        'author'            => 'get_author_template',
        'date'              => 'get_date_template',
        'archive'           => 'get_archive_template',
    ];

    /**
     * Affichage du détail d'une page.
     *
     * @return ResponseContract
     */
    public function index(): ResponseContract
    {
        foreach (array_keys($this->tagTemplates) as $tag) {
            if (call_user_func("is_{$tag}")) {
                if ($response = $this->response($tag, func_get_args())) {
                    return $response;
                }
            }
        }

        return new Response(__('Impossible de charger le gabarit d\'affichage', 'theme'), 404);
    }

    /**
     * Traitement de la reponse HTTP.
     *
     * @param string $tag Indicateur de contexte d'affichage Wordpress.
     * @param array ...$args Liste des arguments dynamiques de requête HTTP
     *
     * @return ResponseContract|null
     */
    public function response(string $tag, ...$args): ?ResponseContract
    {
        $method = Str::camel($tag);

        if (method_exists($this, $method)) {
            return $this->{$method}(...$args);
        } elseif (file_exists(get_template_directory() . "/views/app/{$tag}/index.php")) {
            return $this->viewer("app::{$tag}/index", $this->all());
        } else {
            if ($template = call_user_func($this->tagTemplates[$tag])) {
                if ('attachment' === $tag) {
                    remove_filter('the_content', 'prepend_attachment');
                }
            } else {
                $template = get_index_template();
            }

            if ($template = apply_filters('template_include', $template)) {
                $template = preg_replace(
                    '#' . preg_quote(get_template_directory(), DIRECTORY_SEPARATOR) . '#', '', $template
                );

                return $this->viewer(pathinfo($template, PATHINFO_FILENAME), $this->all());
            }

            return null;
        }
    }
}