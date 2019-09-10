<?php declare(strict_types=1);

namespace tiFy\Contracts\PostType;

use tiFy\Contracts\Support\ParamsBag;

interface PostTypeStatus extends ParamsBag
{
    /**
     * Résolution de sortie de la classe sous forme de chaîne de caractères.
     * {@internal Retourne le nom de qualification du statut.}
     *
     * @return string
     */
    public function __toString(): string;

    /**
     * Récupération ou déclaration d'une instance basé sur le nom de qualification.
     *
     * @param string $name Nom de qualification du status
     *
     * @return static
     */
    public static function createFromName(string $name): PostTypeStatus;

    /**
     * Récupération de l'intitulé de qualification.
     *
     * @return string
     */
    public function getLabel(): string;

    /**
     * Récupération du nom de qualification du statut.
     *
     * @return string
     */
    public function getName(): string;
}