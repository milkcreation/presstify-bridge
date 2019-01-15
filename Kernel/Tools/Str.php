<?php

namespace tiFy\Kernel\Tools;

use Illuminate\Support\Str as IlluminateStr;

/**
 * Class Str
 * @package tiFy\Components\Tools\Str
 * @see \Illuminate\Support\Str
 *
 * @method string after(string $subject, string $search)
 * @method string ascii(string $value, string $language = 'en')
 * @method string before(string $subject, string $search)
 * @method string camel(string $value)
 * @method string contains(string $haystack, string|array $needles)
 * @method string endsWith(string $haystack, string|array $needles)
 * @method string finish(string $value, string $cap)
 * @method string is(string|array $pattern, string $value)
 * @method string kebab(string $value)
 * @method string length(string $value, string $encoding = null)
 * @method string limit(string $value, int $limit = 100, string $end = '...')
 * @method string lower(string $value)
 * @method string words(string $value, int $words = 100, string $end = '...')
 * @method string parseCallback(string $callback, string|null $default = null)
 * @method string plural(string $value, int $count = 2)
 * @method string random(int $length = 16)
 * @method string replaceArray(string $search, array $replace, string $subject)
 * @method string replaceFirst(string $search, string $replace, string $subject)
 * @method string replaceLast(string $search, string $replace, string $subject)
 * @method string start(string $value, string $prefix)
 * @method string upper(string $value)
 * @method string title(string $value)
 * @method string singular(string $value)
 * @method string slug(string $title, string $separator = '-', string $language = 'en')
 * @method string snake(string $value, string $delimiter = '_')
 * @method string startsWith(string $haystack, string|array $needles)
 * @method string studly(string $value)
 * @method string substr(string $string, int $start, int|null $length = null)
 * @method string ucfirst(string $string)
 * @method string uuid()
 * @method string orderedUuid()
 */
class Str
{
    /**
     * Appel statique de l'héritage des méthodes de la classe Str de Laravel.
     * {@internal Utile pour l'appel statique interne à Illuminate\Support\Str}
     *
     * @param string $name Nom de qualification de la méthode.
     * @param array $args Liste des variables passées en argument à la méthode.
     *
     * @return mixed
     */
    public static function __callstatic($name, $args)
    {
        if (method_exists(IlluminateStr::class, $name)) :
            return call_user_func_array([IlluminateStr::class, $name], $args);
        endif;

        return null;
    }

    /**
     * Appel de l'héritage des méthodes statiques de la classe Str de Laravel.
     *
     * @param string $name Nom de qualification de la méthode.
     * @param array $args Liste des variables passées en argument à la méthode.
     *
     * @return mixed
     */
    public function __call($name, $args)
    {
        if (method_exists(IlluminateStr::class, $name)) :
            return call_user_func_array([IlluminateStr::class, $name], $args);
        endif;

        return null;
    }

    /**
     * Création d'un extrait de texte basé sur les nombre de caractères.
     *
     * @param string $string Chaîne de caractère à traiter.
     * @param int $length Nombre maximum de caractères de la chaîne.
     * @param string $teaser Délimiteur de fin de chaîne réduite (ex : [...]).
     * @param string $use_tag Détection d'une balise d'arrêt du type <!--more-->.
     * @param bool $uncut Préservation de la découpe de mots en fin de chaîne.
     *
     * @return string
     */
    public function excerpt($string, $args = array())
    {
        $defaults = array(
            'length' => 255,
            'teaser' => ' [&hellip;]',
            'use_tag' => true,
            'uncut' => true
        );
        $args = wp_parse_args($args, $defaults);
        extract($args);

        $length = abs($length);

        if ($use_tag && preg_match('/<!--more(.*?)?-->/', $string, $matches)) :
            $strings = preg_split('/<!--more(.*?)?-->/', $string);
            $teased = str_replace(']]>', ']]&gt;', $strings[0]);
            $teased = strip_tags($teased);
            $teased = trim($teased);

            if ($length > strlen($teased)) :
                return $teased . $teaser;
            endif;
        else :
            $string = str_replace(']]>', ']]&gt;', $string);
            $string = strip_tags($string);
            $string = trim($string);
            if ($length > strlen($string)) :
                return $string;
            endif;
        endif;

        if ($uncut):
            $string = substr($string, 0, $length);
            $pos = strrpos($string, " ");

            if ($pos === false) :
                return substr($string, 0, $length) . $teaser;
            endif;

            return substr($string, 0, $pos) . $teaser;
        else:
            return substr($string, 0, $length) . $teaser;
        endif;
    }

    /**
     * Compatibilité textarea.
     *
     * @param string $text
     *
     * @return string
     */
    public function br2nl($text)
    {
        return preg_replace(
            '/<br\s?\/?>/ius',
            "\n",
            str_replace(
                "\n",
                "",
                str_replace(
                    "\r",
                    "",
                    htmlspecialchars_decode($text)
                )
            )
        );
    }

    /**
     * Convertion des variables d'environnements d'une chaîne de caractères.
     * @todo
     *
     * @param $output
     * @param array $vars
     * @param string $regex
     *
     * @return null|string|string[]
     */
    public function mergeVars($output, $vars = array(), $regex = "/\*\|(.*?)\|\*/")
    {
        $callback = function ($matches) use ($vars) {
            if (!isset($matches[1])) :
                return $matches[0];
            endif;

            if (isset($vars[$matches[1]])) :
                return $vars[$matches[1]];
            endif;

            return $matches[0];
        };

        $output = preg_replace_callback($regex, $callback, $output);

        return $output;
    }
}