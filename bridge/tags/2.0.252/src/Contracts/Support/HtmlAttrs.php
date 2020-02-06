<?php declare(strict_types=1);

namespace tiFy\Contracts\Support;

interface HtmlAttrs
{
    /**
     * Convertion d'une liste d'attributs en attributs HTML et récupération de la liste sous la forme d'une chaine de
     * caractères ou d'un tableau.
     *
     * @param array $attrs Liste des attributs HTML.
     * @param boolean $linearized Activation de la linéarisation. Sortie sous la forme d'une chaîne de caractères.
     *
     * @return string|array
     */
    public static function createFromAttrs(array $attrs, $linearized = true);

    /**
     * Récupération de la liste des attributs sous la forme d'une chaine de caractères.
     *
     * @return string
     */
    public function __toString(): string;

    /**
     * Récupération de la liste des attributs définis.
     *
     * @return array
     */
    public function all(): array;

    /**
     * Encodage d'une valeur de type array.
     * {@internal La valeur de retour par defaut est exploitable en JS avec JSON.parse(decodeURIComponent({{value}}).}
     *
     * @param array $value
     *
     * @return string
     */
    public function arrayEncode(array $value): string;

    /**
     * Définition d'une liste d'attributs.
     *
     * @param array $attrs Liste des attributs.
     *
     * @return static
     */
    public function set(array $attrs): HtmlAttrs;

    /**
     * Convertion d'un d'attribut en attribut HTML.
     *
     * @param string|array $value Valeur de l'attribut.
     * @param int|string $key Clé d'indice de l'attribut.
     *
     * @return void
     */
    public function walk($value, $key = null): void;
}