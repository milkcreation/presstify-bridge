<?php declare(strict_types=1);

namespace tiFy\Contracts\Auth;

use tiFy\Contracts\Support\ParamsBag;
use tiFy\Contracts\Form\FormFactory;

interface Signin extends ParamsBag
{
    /**
     * Résolution de sortie de la classe en tant que chaîne de caractère.
     * {@internal Affichage du formulaire d'authentification}
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
     * Instance du formulaire.
     *
     * @return FormFactory|null
     */
    public function form(): ?FormFactory;

    /**
     * Récupération de l'url de redirection du formulaire d'authentification.
     *
     * @param string|null $redirect_url Url de redirection personnalisée.
     *
     * @return string
     */
    public function getAuthRedirectUrl(?string $redirect_url = null): string;

    /**
     * Récupération de l'url de redirection du formulaire d'authentification.
     *
     * @param string|null Url de redirection personnalisée.
     *
     * @return string
     */
    public function getLogoutRedirectUrl(?string $redirect_url = null): string;

    /**
     * Récupération de l'url de déconnection.
     *
     * @param string|null Url de redirection personnalisée.
     *
     * @return string
     */
    public function getLogoutUrl(?string $redirect_url = null): string;

    /**
     * Récupération du nom de qualification du controleur.
     *
     * @return string
     */
    public function getName(): string;

    /**
     * Récupération de la liste des rôles autorisés à se connecter depuis l'interface de login.
     *
     * @return array
     */
    public function getRoles(): array;

    /**
     * Traitement.
     *
     * @return void
     */
    public function handle(): void;

    /**
     * Vérification d'autorisation de connection de rôle(s) utilisateur donné(s).
     *
     * @param string|array $role Nom de qualification ou liste des nom de qualification des roles à vérifier.
     *
     * @return boolean
     */
    public function hasRole($role): bool;

    /**
     * {@inheritDoc}
     *
     * @return Signin
     */
    public function parse(): Signin;

    /**
     * Préparation de la classe.
     *
     * @param string $name Nom de qualification.
     * @param array $attrs Liste des attributs de configuration.
     *
     * @return static
     */
    public function prepare(string $name, array $attrs = []): Signin;

    /**
     * Affichage du formulaire d'authentification.
     *
     * @return string
     */
    public function renderForm(): string;

    /**
     * Affichage du lien de déconnection.
     *
     * @return string
     */
    public function renderLogout(): string;

    /**
     * Affichage du lien vers l'interface de récupération de mot de passe oublié.
     *
     * @return string
     */
    public function renderLostpassword(): string;
}