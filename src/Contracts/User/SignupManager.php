<?php declare(strict_types=1);

namespace tiFy\Contracts\User;

interface SignupManager
{
    /**
     * Récupération d'un formulaire d'inscription.
     *
     * @param string $name Nom de de qualification.
     *
     * @return SignupFactory|null
     */
    public function get(string $name): ?SignupFactory;

    /**
     * Déclaration d'un formulaire d'inscription.
     *
     * @param string $name Nom de qualification.
     * @param array $attrs Liste des attributs de configuration.
     *
     * @return static
     */
    public function register(string $name, array $attrs): SignupManager;

    /**
     * Définition d'un formulaire d'inscription.
     *
     * @param SignupFactory $factory Instance du formulaire.
     * @param string|null $name Nom de qualification.
     *
     * @return static
     */
    public function set(SignupFactory $factory, ?string $name = null): SignupManager;
}