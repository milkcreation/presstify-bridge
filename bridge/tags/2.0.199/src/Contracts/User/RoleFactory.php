<?php declare(strict_types=1);

namespace tiFy\Contracts\User;

use tiFy\Contracts\Support\ParamsBag;

interface RoleFactory extends ParamsBag
{
    /**
     * Initialisation du controleur.
     *
     * @return void
     */
    public function boot(): void;

    /**
     * Récupération de l'intitulé d'affichage.
     *
     * @return string
     */
    public function getDisplayName(): string;

    /**
     * Récupération du nom de qualification.
     *
     * @return string
     */
    public function getName(): string;

    /**
     * Préparation du controleur.
     *
     * @param RoleManager $manager Instance du gestionnaire de rôle.
     * @param string $name Nom de qualification du rôle.
     * @param array $attrs Liste des attributs de configuration.
     *
     * @return static
     */
    public function prepare(RoleManager $manager, ?string $name = null, array $attrs = []): RoleFactory;
}