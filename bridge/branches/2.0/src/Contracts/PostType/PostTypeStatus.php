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
     * Récupération ou modification d'une instance.
     *
     * @param string $name Nom de qualification du statut.
     * @param array $args Liste des arguments de configuration.
     *
     * @return static
     */
    public static function create(string $name, array $args = []): PostTypeStatus;

    /**
     * Récupération d'une instance déclarée.
     *
     * @param string $name Nom de qualification du statut.
     *
     * @return static|null
     */
    public static function instance(string $name): ?PostTypeStatus;

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