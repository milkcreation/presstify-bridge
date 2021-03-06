<?php declare(strict_types=1);

namespace tiFy\Wordpress\Contracts;

use tiFy\Contracts\Support\ParamsBag;
use tiFy\Wordpress\Contracts\Query\QueryPost;
use WP_Post;

interface PageHookItem extends ParamsBag
{
    /**
     * Vérification d'existance du post associé.
     *
     * @return boolean
     */
    public function exists(): bool;

    /**
     * Récupération du la description.
     *
     * @return string
     */
    public function getDescription(): string;

    /**
     * Récupération du nom de qualification.
     *
     * @return string
     */
    public function getName(): string;

    /**
     * Récupération du chemin relatif vers la page d'affichage du post associé.
     *
     * @return string
     */
    public function getPath(): string;

    /**
     * Récupération du type d'objet Wordpress.
     *
     * @return string
     */
    public function getObjectType(): string;

    /**
     * Récupération du nom de qualification de l'objet Wordpress
     *
     * @return string
     */
    public function getObjectName(): string;

    /**
     * Récupération du nom de qualification d'enregistrement en base de donnée.
     *
     * @return string
     */
    public function getOptionName(): string;

    /**
     * Récupération de l'intitulé de qualification.
     *
     * @return string
     */
    public function getTitle(): string;

    /**
     * Vérifie si une page est associée à la page d'accroche.
     *
     * @param WP_Post|null $post Page d'affichage courante|Identifiant de qualification|Objet post Wordpress à vérifier.
     *
     * @return bool
     */
    public function is(?WP_Post $post = null): bool;

    /**
     * Vérifie si la page courante hérite de la page d'accroche.
     *
     * @return bool
     */
    public function isAncestor(): bool;

    /**
     * Récupération de l'instance du post associé.
     *
     * @return QueryPost|null
     */
    public function post(): ?QueryPost;
}