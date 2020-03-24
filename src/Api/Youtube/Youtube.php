<?php

/**
 * @see https://github.com/madcoda/php-youtube-api
 * @see https://github.com/oscarotero/Embed
 * @see https://oscarotero.com/embed3/demo/index.php
 */

namespace tiFy\Api\Youtube;

use Exception;
use Illuminate\Support\Arr;
use Embed\Embed;
use Madcoda\Youtube\Youtube as MadcodaYoutube;
use tiFy\Support\Proxy\{Partial, View};
use tiFy\Validation\Validator as v;
use WP_Error;

class Youtube extends MadcodaYoutube
{
    /**
     * Instance de la classe.
     * @var static|null
     */
    protected static $instance;

    /**
     * CONSTRUCTEUR.
     *
     * @param array $params Liste des paramètres.
     * @param string $sslPath
     *
     * @return void
     *
     * @throws Exception
     */
    protected function __construct($params = [], $sslPath = null)
    {
        parent::__construct($params, $sslPath);
    }

    /**
     * Instanciation.
     *
     * @param array $attrs
     *
     * @return static
     *
     * @throws Exception
     */
    public static function create(array $attrs = [])
    {
        if (!self::$instance) {
            self::$instance = new static($attrs, is_ssl());
        }

        return self::$instance;
    }

    /**
     * Vérification de correspondance d'url.
     *
     * @param string $url Url de la vidéo.
     *
     * @return string
     */
    public static function isUrl($url)
    {
        return preg_match('#^https?:\/\/(?:www\.)?(?:youtube\.com/watch|youtu\.be/)#', $url);
    }

    /**
     * Récupération du code d'intégration d'une vidéo.
     *
     * @param string $video Identifiant ou url de la vidéo.
     * @param array $params {
     *      Liste des paramètres.
     *      @see https://developers.google.com/youtube/player_parameters?hl=fr#Parameters
     * }
     *
     * @return string
     */
    public function getEmbed($video, $params = [])
    {
        if(v::url()->validate($video)) {
            try {
                $video_id = self::parseVIdFromURL($video);
                $video_url = $video;
            } catch (Exception $e) {
                return '';
            }
        } else {
            try {
                $video_id = $video;
                $video_url = $this->getUrlFromId($video);
            } catch (Exception $e) {
                return '';
            }
        }

        try {
            $info = Embed::create($video_url);

            if (Arr::get($params, 'loop')) {
                Arr::set($params, 'playlist', $video_id);
            }

            $height = $info->getHeight();
            $ratio = $info->getAspectRatio();
            $src = esc_url("//www.youtube.com/embed/{$video_id}" . ($params ? '?' . http_build_query($params) : ''));
            $width = $info->getWidth();

            return View::getPlatesEngine(['directory' => __DIR__ . '/views'])->render(
                'iframe',
                compact('height', 'ratio', 'src', 'width', 'params')
            );
        } catch (Exception $e) {
            return '';
        }
    }

    /**
     * Récupération des données de miniature.
     *
     * @param string $video Identifiant ou url de la vidéo.
     * @param array $formats Format de l'image, par ordre de préférence. maxres|standard|height|medium|default.
     *
     * @return array|WP_Error
     *
     * @throws Exception
     */
    public function getThumbnailSrc($video, $formats = [])
    {
        if(v::url()->validate($video)) {
            if (!self::isUrl($video)) {
                return new WP_Error(
                    'tFyComponentsApiYtInvalidSrc',
                    __('Url YouTube invalide', 'tify')
                );
            } elseif (!$video = self::parseVIdFromURL($video)) {
                return new WP_Error(
                    'tFyComponentsApiYtParseVIdFailed',
                    __('Récupération de l\ID de la vidéo depuis l\'url en échec', 'tify')
                );
            }
        }

        if (!$infos = $this->getVideoInfo($video)) {
            return new WP_Error(
                'tFyComponentsApiYtGetVideoInfos',
                __('Impossible de récupérer les informations de la vidéo', 'tify')
            );
        }

        if (empty($infos->snippet->thumbnails)) {
            return new WP_Error(
                'tFyComponentsApiYtAnyThumbnailAvailable',
                __('Aucune miniature disponible', 'tify')
            );
        }

        if (empty($formats)) {
            $formats = array_keys(get_object_vars($infos->snippet->thumbnails));
        }

        foreach ($formats as $format) {
            if (empty($infos->snippet->thumbnails->{$format})) {
                continue;
            }

            $attrs = get_object_vars($infos->snippet->thumbnails->{$format});
            if (empty($attrs['url']) || empty($attrs['width']) || empty($attrs['height'])) {
                continue;
            }

            $attrs['src'] = $attrs['url'];
            unset($attrs['url']);

            $attrs['title'] = $infos->snippet->title;

            $src[$format] = $attrs;
        }

        return !empty($src) ? $src : [];
    }

    /**
     * Récupération des données de miniature.
     *
     * @param string $video Identifiant ou url de la vidéo.
     * @param string $size Taille de l'image.
     *
     * @return string
     *
     * @throws Exception
     */
    public function getThumbnailImg($video, $size = 'default')
    {
        $attrs = $this->getThumbnailSrc($video, [$size]);

        if ($attrs && !is_wp_error($attrs)) {
            $attrs = reset($attrs);

            return (string)Partial::get('tag', [
                'tag'   => 'img',
                'attrs' => $attrs
            ]);
        } else {
            return '';
        }
    }

    /**
     * Récupération de l'url à partir de l'Id de la video.
     *
     * @param string $id Identifiant de la video.
     *
     * @return string
     *
     * @throws Exception
     */
    public function getUrlFromId($id)
    {
        $url = "https://www.youtube.com/watch?v={$id}";

        try {
            self::parseVIdFromURL($url);

            return $url;
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }
}