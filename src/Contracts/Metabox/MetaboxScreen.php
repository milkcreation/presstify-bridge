<?php declare(strict_types=1);

namespace tiFy\Contracts\Metabox;

use tiFy\Contracts\Support\ParamsBag;

interface MetaboxScreen extends ParamsBag
{
    /**
     * Récupération de la liste des boîtes de saisie associées à l'écran.
     *
     * @return MetaboxDriver[]|array
     */
    public function getMetaboxes(): array;

    /**
     * Vérifie si la page courante correspond à l'écran.
     *
     * @return boolean
     */
    public function isCurrent(): bool;

    /**
     * Vérifie si la route courante correspond à l'écran.
     *
     * @return boolean
     */
    public function isCurrentRoute(): bool;

    /**
     * Vérifie si la requête courante correspond à l'écran.
     *
     * @return boolean
     */
    public function isCurrentRequest(): bool;

    /**
     * Récupération de l'instance du gestionnaire.
     *
     * @return MetaboxManager|null
     */
    public function manager(): ?MetaboxManager;

    /**
     * Définition de l'instance du gestionnaire.
     *
     * @param MetaboxManager $manager
     *
     * @return static
     */
    public function setManager(MetaboxManager $manager): MetaboxScreen;

    /**
     * Définition du nom de qualification.
     *
     * @param string $name
     *
     * @return static
     */
    public function setName(string $name): MetaboxScreen;
}