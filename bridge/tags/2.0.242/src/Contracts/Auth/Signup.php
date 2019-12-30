<?php declare(strict_types=1);

namespace tiFy\Contracts\Auth;

use tiFy\Contracts\Support\ParamsBag;
use tiFy\Contracts\Form\FormFactory;

interface Signup extends ParamsBag
{
    /**
     * Résolution de sortie de la classe en tant que chaîne de caractère.
     * {@internal Affiche le formulaire}
     *
     * @return string
     */
    public function __toString(): string;

    /**
     * Initialisation de la classe.
     *
     * @return void
     */
    public function boot(): void;

    /**
     * Récupération du nom de qualification du controleur.
     *
     * @return string
     */
    public function getName(): string;

    /**
     * Récupération du formulaire.
     *
     * @return FormFactory|null
     */
    public function form(): ?FormFactory;

    /**
     * Préparation de la classe.
     *
     * @param string $name Nom de qualification.
     * @param array $attrs Liste des attributs de configuration.
     *
     * @return static
     */
    public function prepare(string $name, array $attrs = []): Signup;
}