<?php

namespace tiFy\Wordpress\Media;

use tiFy\Contracts\Encryption\Encrypter;
use tiFy\Wordpress\Contracts\Download as DownloadContract;
use WP_Post;

class Download implements DownloadContract
{
    /**
     * Liste des fichiers autorisés au téléchargement
     * @var array
     */
    private static $allowed = [];

    /**
     * Instance du contrôleur d'encryptage.
     * @var Encrypter
     */
    protected $encrypter;

    /**
     * CONSTRUCTEUR.
     *
     * @return void
     */
    public function __construct()
    {
        add_action('init', function () {
            $this->encrypter = app('encrypter');
        });

        add_action('admin_init', function () {
            $this->_process();
        });

        add_action('template_redirect', function () {
            $this->_process();
        });

        add_filter('media_row_actions', function ($actions, WP_Post $post, $detached) {
            $actions['wp_media_dl'] = "<a href=\"" . $this->url($post->ID) . "\">" . __('Télécharger', 'tify') . "</a>";
            return $actions;
        }, 99, 3);

        events()->on('wp.media.download.register', function ($abspath, DownloadContract $mediaDownload) {
            if (in_array($abspath, self::$allowed)) {
                return;
            } elseif (!$token = request()->get('wp_media_dl', false)) {
                return;
            } elseif (is_admin()) {
                if (!$_wp_nonce = request()->get('_wpnonce', false)) {
                    return;
                } elseif (wp_verify_nonce($_wp_nonce, "wp.media.download.{$token}")) {
                    $this->register($abspath);
                }
            } else {
                $media = $this->encrypter->decrypt($token);

                if (is_numeric($media) && get_post_meta($media, '_tify_media_download_token', true) === $token) {
                    $this->register($abspath);
                }
            }
        });
    }

    /**
     * @inheritdoc
     */
    public function register($file)
    {
        if (preg_match('#^' . preg_quote(ABSPATH, DIRECTORY_SEPARATOR) . '#', $file)) :
            $abspath = $file;
        else :
            if (is_numeric($file)) :
                $url = wp_get_attachment_url(absint($file));
            else :
                $url = $file;
            endif;

            $rel = trim(preg_replace('/' . preg_quote(network_site_url('/'), '/') . '/', '', $url), '/');
            $abspath = ABSPATH . $rel;
        endif;

        if (!file_exists($abspath)) :
            return;
        endif;

        if (!in_array($abspath, self::$allowed)) :
            array_push(self::$allowed, $abspath);
        endif;
    }

    /**
     * @inheritdoc
     */
    public function url($file, $query_vars = [])
    {
        $vars = [];
        if (is_admin()) :
            $baseurl = wp_nonce_url(admin_url('/'), "wp.media.download.{$file}");
            $vars['wp_media_dl'] = is_int($file) ? $file : urlencode_deep($file);
        else :
            $baseurl = home_url('/');
            $token = $this->encrypter->encrypt($file);
            if (is_numeric($file)) :
                if ($token !== get_post_meta($file, '_tify_media_download_token', true)) :
                    update_post_meta($file, '_tify_media_download_token', $token);
                endif;
            endif;

            $vars['wp_media_dl'] = $token;
        endif;

        return add_query_arg(array_merge($query_vars, $vars), $baseurl);
    }

    /**
     * Téléchargement du fichier.
     *
     * @return void
     */
    private function _process()
    {
        if (!$media = request()->get('wp_media_dl', false)) :
            return;
        elseif (!is_admin()) :
            $media = $this->encrypter->decrypt($media);
        endif;

        $url = is_numeric($media) ? wp_get_attachment_url($media) : urldecode($media);

        // L'url du fichier média n'est pas valide
        if (!isset($url)) {
            wp_die(
                '<h1>' . __('Téléchargement du fichier impossible', 'tify') . '</h1>' .
                '<p>' . __('L\'url du fichier média n\'est pas valide', 'tify') . '</p>',
                __('Impossible de trouver le fichier', 'tify'),
                404
            );
        }

        if(preg_match('/^' . preg_quote(site_url('/'), '/') .'/', $url)) {
            $abspath = preg_replace('/^' . preg_quote(site_url('/'), '/') .'/', ABSPATH, $url);
        } elseif(preg_match('/^' . preg_quote(network_site_url('/'), '/') .'/', $url)) {
            $abspath = preg_replace('/^' . preg_quote(network_site_url('/'), '/') .'/', ABSPATH, $url);
        } else {
            $abspath = $url;
        }

        // Le fichier n'existe pas
        if (!file_exists($abspath)) {
            wp_die(
                '<h1>' . __('Téléchargement du fichier impossible', 'tify') . '</h1>' .
                '<p>' . __('Le fichier n\'existe pas', 'tify') . '</p>',
                __('Impossible de trouver le fichier', 'tify'),
                404
            );
        }

        // Le type du fichier est indeterminé ou non référencé
        $fileinfo = wp_check_filetype($abspath, wp_get_mime_types());
        if (empty($fileinfo['ext']) || empty($fileinfo['type'])) {
            wp_die(
                '<h1>' . __('Téléchargement du fichier impossible', 'tify') . '</h1>' .
                '<p>' . __('Le type du fichier est indeterminé ou non référencé', 'tify') . '</p>',
                __('Type de fichier erroné', 'tify'),
                400
            );
        }

        // Le type de fichier est interdit
        if (!in_array($fileinfo['type'], get_allowed_mime_types())) {
            wp_die(
                '<h1>' . __('Téléchargement du fichier impossible', 'tify') . '</h1>' .
                '<p>' . __('Le type de fichier est interdit', 'tify') . '</p>',
                __('Type de fichier interdit', 'tify'),
                405
            );
        }

        // Déclaration des permissions de téléchargement de fichier
        events()->trigger('wp.media.download.register', [$abspath, &$this]);

        // Bypass - Le téléchargement de ce fichier n'est pas autorisé
        if (!in_array($abspath, self::$allowed)) {
            wp_die(
                '<h1>' . __('Téléchargement du fichier impossible', 'tify') . '</h1>' .
                '<p>' . __('Le téléchargement de ce fichier n\'est pas autorisé', 'tify') . '</p>',
                __('Téléchargement interdit', 'tify'),
                401
            );
        }

        // Définition de la taille du fichier
        $filesize = @ filesize($abspath);
        $rangefilesize = $filesize - 1;

        if (ini_get('zlib.output_compression')) {
            ini_set('zlib.output_compression', 'Off');
        }

        clearstatcache();
        nocache_headers();
        ob_start();
        ob_end_clean();

        header("Pragma: no-cache");
        header("Expires: 0");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0, public, max-age=0");
        header("Content-Description: File Transfer");
        header("Accept-Ranges: bytes");

        if ($filesize) :
            header("Content-Length: " . (string)$filesize);
        endif;
        if ($filesize && $rangefilesize) :
            header("Content-Range: bytes 0-" . (string)$rangefilesize . "/" . (string)$filesize);
        endif;

        if (isset($fileinfo['type'])) :
            header("Content-Type: " . (string)$fileinfo['type']);
        else :
            header("Content-Type: application/force-download");
        endif;

        header("Content-Disposition: attachment; filename=" . str_replace(' ', '\\', basename($abspath)));
        //header("Content-Transfer-Encoding: {$fileinfo['type']}\n");

        @ set_time_limit(0);

        $fp = @ fopen($abspath, 'rb');
        if ($fp !== false) :
            while (!feof($fp)) :
                echo fread($fp, 8192);
            endwhile;
            fclose($fp);
        else :
            @ readfile($abspath);
        endif;
        ob_flush();

        events()->trigger('wp.media.download.after', [$abspath, &$this]);

        exit;
    }
}