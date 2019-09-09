<?php declare(strict_types=1);

namespace tiFy\Wordpress\Contracts;

use tiFy\Contracts\Support\ParamsBag;
use tiFy\Contracts\Routing\Route;
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
     * Vérifie si la page d'affichage courante correspond à la page d'accroche associée.
     *
     * @param WP_Post|null Page d'affichage courante|Identifiant de qualification|Objet post Wordpress à vérifier.
     *
     * @return bool
     */
    public function is(?WP_Post $post = null);

    /**
     * Récupération de l'instance du post associé.
     *
     * @return QueryPost|null
     */
    public function post(): ?QueryPost;

    /**
     * Récupération de l'intance de la route associée.
     *
     * @return Route|null
     */
    public function route(): ?Route;
}