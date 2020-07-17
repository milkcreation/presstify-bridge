<?php declare(strict_types=1);

namespace tiFy\Support;

use Illuminate\Support\Str as BaseStr;
use tiFy\Validation\Validator as v;

class Str extends BaseStr
{
    /**
     * @deprecated
     */
    public static function excerpt(
        string $string,
        int $length = 255,
        string $teaser = ' [&hellip;]',
        bool $use_tag = true,
        bool $uncut = true
    ) {
        return static::teaser($string, $length, $teaser, $use_tag, $uncut);
    }

    /**
     * Création d'un extrait de texte basé sur les nombre de caractères.
     *
     * @param string $string Chaîne de caractère à traiter.
     * @param int $length Nombre maximum de caractères de la chaîne.
     * @param string $teaser Délimiteur de fin de chaîne réduite (defaut : [...]).
     * @param boolean $use_tag Détection d'une balise d'arrêt du type <!--more-->.
     * @param boolean $uncut Préservation de la découpe de mots en fin de chaîne.
     *
     * @return string
     */
    public static function teaser(
        string $string,
        int $length = 255,
        string $teaser = ' [&hellip;]',
        bool $use_tag = true,
        bool $uncut = true
    ) {
        if ($use_tag && preg_match('/<!--more(.*?)?-->/', $string, $matches)) {
            $strings = preg_split('/<!--more(.*?)?-->/', $string);
            $teased = str_replace(']]>', ']]&gt;', $strings[0]);
            $teased = strip_tags($teased);
            $teased = trim($teased);

            if ($length > strlen($teased)) {
                return $teased . $teaser;
            } else {
                $string = $teased;
            }
        } else {
            $string = str_replace(']]>', ']]&gt;', $string);
            $string = strip_tags($string);
            $string = trim($string);

            if ($length > strlen($string)) {
                return $string;
            }
        }

        if ($uncut) {
            $string = substr($string, 0, $length);
            $pos = strrpos($string, " ");

            if ($pos === false) {
                return substr($string, 0, $length) . $teaser;
            }

            return substr($string, 0, $pos) . $teaser;
        } else {
            return substr($string, 0, $length) . $teaser;
        }
    }

    /**
     * Compatibilité textarea.
     *
     * @param string $text
     *
     * @return string
     */
    public static function br2nl($text)
    {
        return preg_replace('/<br\s?\/?>/ius', "\n", str_replace(
            "\n", "", str_replace("\r", "", htmlspecialchars_decode($text))
        ));
    }

    /**
     * Fermeture des balise HTML non fermée.
     *
     * @param $html
     *
     * @return string
     *
     * @see https://gist.github.com/JayWood/348752b568ecd63ae5ce
     */
    public static function closeTags($html) {
        preg_match_all('#<([a-z]+)(?: .*)?(?<![/|/ ])>#iU', $html, $result);
        $openedtags = $result[1];
        preg_match_all('#</([a-z]+)>#iU', $html, $result);

        $closedtags = $result[1];
        $len_opened = count($openedtags);

        if (count($closedtags) == $len_opened) {
            return $html;
        }

        $openedtags = array_reverse($openedtags);

        for ($i=0; $i < $len_opened; $i++) {
            if (!in_array($openedtags[$i], $closedtags)) {
                $html .= '</'.$openedtags[$i].'>';
            } else {
                unset($closedtags[array_search($openedtags[$i], $closedtags)]);
            }
        }

        return $html;
    }

    /**
     * Convertion des variables d'environnements d'une chaîne de caractères.
     *
     * @param $output
     * @param array $vars
     * @param string $regex
     *
     * @return null|string|string[]
     */
    public static function mergeVars($output, $vars = [], $regex = "/\*\|(.*?)\|\*/")
    {
        $callback = function ($matches) use ($vars) {
            if (!isset($matches[1])) {
                return $matches[0];
            }

            if (isset($vars[$matches[1]])) {
                return $vars[$matches[1]];
            }

            return $matches[0];
        };

        $output = preg_replace_callback($regex, $callback, $output);

        return $output;
    }

    /**
     * Déserialisation d'un chaine de caractère.
     *
     * @param string $value Chaîne de caractère à convertir.
     *
     * @return mixed
     */
    public static function unserialize(string $value)
    {
        if (v::serialized()->validate($value)) {
            return @unserialize($value);
        }

        return $value;
    }
}