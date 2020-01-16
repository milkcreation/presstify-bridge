<?php declare(strict_types=1);

namespace tiFy\Wordpress\Routing;

use tiFy\Wordpress\Contracts\WpQuery as WpQueryContract;
use WP_Query;

class WpQuery implements WpQueryContract
{
    /**
     * Liste des indicateurs de condition permis.
     * @see https://codex.wordpress.org/Conditional_Tags
     * @var array
     */
    protected $ctags = [
        '404'               => 'is_404',
        'archive'           => 'is_archive',
        'attachment'        => 'is_attachment',
        'author'            => 'is_author',
        'category'          => 'is_category',
        'date'              => 'is_date',
        'day'               => 'is_day',
        'front'             => 'is_front_page',
        'home'              => 'is_home',
        'month'             => 'is_month',
        'page'              => 'is_page',
        'paged'             => 'is_paged',
        'post_type_archive' => 'is_post_type_archive',
        'search'            => 'is_search',
        'single'            => 'is_single',
        'singular'          => 'is_singular',
        'sticky'            => 'is_sticky',
        'tag'               => 'is_tag',
        'tax'               => 'is_tax',
        'template'          => 'is_template',
        'time'              => 'is_time',
        'year'              => 'is_year'
    ];

    /**
     * CONSTRUCTEUR.
     *
     * @return void
     */
    public function __construct()
    {
        add_action('pre_get_posts', function (WP_Query &$wp_query) {
            if ($wp_query->is_main_query()) {
                foreach (config('wp.query', []) as $ctag => $query_args) {
                    if (in_array($ctag, $this->ctags) && call_user_func([$wp_query, $ctag])) {
                        foreach ($query_args as $query_arg => $value) {
                            $wp_query->set($query_arg, $value);
                        }
                    }
                }
            }
            events()->trigger('wp.query', [&$wp_query]);
        });
    }

    /**
     * @inheritdoc
     */
    public function is($ctag): bool
    {
        if (preg_match('/^([\w]+)@wp$/', $ctag, $matches)) {
            $ctag = $matches[1];
        }
        return isset($this->ctags[$ctag]) ? call_user_func($this->ctags[$ctag]) : false;
    }

    /**
     * @inheritdoc
     */
    public function ctag(): ?string
    {
        if (is_404()) {
            return '404';
        } elseif (is_search()) {
            return 'search';
        } elseif (is_front_page()) {
            return 'front';
        } elseif (is_home()) {
            return 'home';
        } elseif (is_post_type_archive()) {
            return 'post_type_archive';
        } elseif (is_tax()) {
            return 'tax';
        } elseif (is_attachment()) {
            return 'attachment';
        } elseif (is_single()) {
            return 'single';
        } elseif (is_page()) {
            return 'page';
        } elseif (is_singular()) {
            return 'singular';
        } elseif (is_category()) {
            return 'category';
        } elseif (is_tag()) {
            return 'tag';
        } elseif (is_author()) {
            return 'author';
        } elseif (is_date()) {
            return 'date';
        } elseif (is_archive()) {
            return 'archive';
        } else {
            return null;
        }
    }
}