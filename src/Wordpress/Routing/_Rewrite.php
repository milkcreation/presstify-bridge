<?php

namespace tiFy\Wordpress\Rewrite;

/**
 * Class Rewrite
 * @package tiFy\Wordpress\Rewrite
 *
 * @see var_dump(get_option('rewrite_rules'));
 */
class Rewrite
{
    /**
     * @inheritdoc
     */
    public function boot()
    {
        add_filter('post_type_link', function (string $post_link, \WP_Post $post) {
            if (
                ($post->post_type === 'publication') &&
                ($hook = page_hook('partners')) &&
                ($partner_id = get_post_meta($post->ID, '_related_partner', true)) &&
                ($partner = get_post($partner_id))
            ) :
                return $hook->post()->getPermalink() . $partner->post_name . '/' . $post->post_name;
            elseif (
                ($post->post_type === 'publication') &&
                ($hook = page_hook('publications'))
            ) :
                return $hook->post()->getPermalink() . $post->post_name;
            elseif (
                ($post->post_type === 'partner') &&
                ($hook = page_hook('partners'))
            ) :
                return $hook->post()->getPermalink() . $post->post_name;
            endif;

            return $post_link;
        }, 99999, 4);

        add_filter('query_vars', function($vars){
            $vars[] = '_paged';

            return $vars;
        }, 999999);

        add_action('init', function () {
            $this->partnersRewriteRules();
            $this->pubsRewriteRules();
        });

        add_action('current_screen',  function (\WP_Screen $wp_screen) {
            if ($wp_screen->id !== 'settings_page_tify_options') :
                flush_rewrite_rules();
            endif;
        });

        add_action('save_post', function(int $post_id) {
            if (page_hook('partners')->is($post_id) || page_hook('publications')->is($post_id)) :
                flush_rewrite_rules();
            endif;
        }, 999999, 2);

        add_action('edit_form_top', function (\WP_Post $post) {
            $label = '';
            if (page_hook('partners')->is($post)) :
                $label = post_type('partner')->label('plural');
            elseif (page_hook('publications')->is($post)) :
                $label = post_type('publication')->label('plural');
            endif;

            if ($label) :
                echo "<div class=\"notice notice-info inline\">\n" .
                    "\t<p>" .
                    sprintf(__('Vous éditez actuellement la page d\'affichage des %s', 'tify'), $label) .
                    "</p>\n" .
                    "</div>";
            endif;
        });

        add_filter('display_post_states', function (array $post_states, \WP_Post $post) {
            $label = '';
            if (page_hook('partners')->is($post)) :
                $label = post_type('partner')->label('plural');
            elseif (page_hook('publications')->is($post)) :
                $label = post_type('publication')->label('plural');
            endif;

            if ($label) :
                $post_states[] = sprintf(__('Page d\'affichage des %s', 'tify'),$label);
            endif;

            return $post_states;
        }, 10, 2);
    }

    /**
     * Régles de réécriture des pages client.
     *
     * @return void
     */
    public function partnersRewriteRules()
    {
        global $wp_rewrite;

        if ($page_for_partners = page_hook('partners')->post()) :
            add_rewrite_rule(
                $page_for_partners->post_name . '/([^/]+)/?$',
                'index.php?post_type=partner&name=$matches[1]',
                'top'
            );

            add_rewrite_rule(
                $page_for_partners->post_name . '/([^/]+)/' . $wp_rewrite->pagination_base . '/([0-9]{1,})/?$',
                'index.php?post_type=partner&name=$matches[1]&_paged=$matches[2]',
                'top'
            );

            add_rewrite_rule(
                $page_for_partners->post_name . '/([^/]+)/([^/]+)/?$',
                'index.php?post_type=publication&name=$matches[2]',
                'bottom'
            );
        endif;
    }

    /**
     * Régles de réécriture des pages publication.
     *
     * @return void
     */
    public function pubsRewriteRules()
    {
        global $wp_rewrite;

        if ($page_for_pub = page_hook('publications')->post()) :
            add_rewrite_rule(
                $page_for_pub->post_name . '/([^/]+)/?$',
                'index.php?post_type=publication&name=$matches[1]',
                'top'
            );

            add_rewrite_rule(
                $page_for_pub->post_name . '/' . $wp_rewrite->pagination_base . '/([0-9]{1,})/?$',
                'index.php?page_id='. $page_for_pub->ID . '&paged=$matches[1]',
                'top'
            );
        endif;
    }
}