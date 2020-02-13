<?php declare(strict_types=1);

namespace tiFy\Contracts\Asset;

use Psr\Container\ContainerInterface;
use tiFy\Contracts\Support\ParamsBag;

interface Asset extends ParamsBag
{
    /**
     * Récupération des scripts du pied de page du site.
     *
     * @return string
     */
    public function footer(): string;

    /**
     * Récupération des scripts de l'entête du site.
     *
     * @return string
     */
    public function header(): string;

    /**
     * Récupération de l'instance du conteneur d'injection de dépendances.
     *
     * @return ContainerInterface
     */
    public function getContainer(): ContainerInterface;

    /**
     * Normalisation d'une chaîne de caractères JS ou CSS.
     *
     * @param string $string
     *
     * @return string
     */
    public function normalize(string $string): string;

    /**
     * Définition de styles CSS en ligne.
     *
     * @param string $css propriétés CSS.
     *
     * @return static
     */
    public function setInlineCss(string $css): Asset;

    /**
     * Définition de styles JS.
     *
     * @param string $js propriétés Js.
     * @param boolean $footer Position d'affichage du script. false entête|true pied de page du site.
     *
     * @return static
     */
    public function setInlineJs(string $js, bool $footer = false): Asset;

    /**
     * Définition d'attributs JS.
     *
     * @param string $key Clé d'indexe de l'attribut à ajouter.
     * @param mixed $value Valeur de l'attribut.
     * @param boolean $footer Ecriture des attributs dans le pied de page du site.
     *
     * @return static
     */
    public function setDataJs(string $key, $value, bool $footer = true): Asset;

    /**
     * Récupération de l'url vers un asset.
     *
     * @param string $path Chemin relatif vers le fichier du dossier des assets.
     *
     * @return string
     */
    public function url(string $path): string;
}
