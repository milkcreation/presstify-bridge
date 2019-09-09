<?php

namespace tiFy\Wordpress\Media;

class Media
{
    /**
     * CONSTRUCTEUR.
     *
     * @return void
     */
    public function __construct()
    {
        app()->get('wp.media.download');

        app()->get('wp.media.upload');

        /**
         * @see wp_get_attachment_url()
         */
        add_filter('wp_get_attachment_url', function ($url, $post_id) {
            if (!$metadata = get_post_meta($post_id, '_wp_attachment_metadata', true)) :
                return $url;
            endif;
            if (!isset($metadata['upload_dir'])) :
                return $url;
            endif;

            if ($file = get_post_meta($post_id, '_wp_attached_file', true)) :
                $url = $metadata['upload_dir']['baseurl'] . "/$file";
            else :
                $url = get_the_guid($post_id);
            endif;

            if (is_ssl() && !is_admin() && 'wp-login.php' !== $GLOBALS['pagenow']) :
                $url = set_url_scheme($url);
            endif;

            return $url;
        }, 10, 2);

        /**
         * @see get_attached_file()
         */
        add_filter('get_attached_file', function ($file, $attachment_id) {
            if (!$metadata = get_post_meta($attachment_id, '_wp_attachment_metadata', true)) :
                return $file;
            endif;
            if (!isset($metadata['upload_dir'])) :
                return $file;
            endif;

            $file = get_post_meta($attachment_id, '_wp_attached_file', true);
            $file = "{$metadata['upload_dir']['basedir']}/{$file}";

            return $file;
        }, 10, 2);

        /**
         * Calcul des sources images inclus dans l'attribut 'srcset'.
         * @see wp_calculate_image_srcset()
         */
        add_filter('wp_calculate_image_srcset', function ($sources, $size_array, $image_src, $image_meta, $attachment_id) {
            if (! $metadata = \get_post_meta($attachment_id, '_wp_attachment_metadata', true)) :
                return $sources;
            endif;
            if (! isset($metadata['upload_dir'])) :
                return $sources;
            endif;

            foreach($sources as &$attrs) :
                $attrs['url'] = $metadata['upload_dir']['url'] . '/' . basename($attrs['url']);
            endforeach;

            return $sources;
        }, 10, 5);
    }
}