<?php declare(strict_types=1);

namespace tiFy\Contracts\User;

use tiFy\Contracts\Support\ParamsBag;
use tiFy\Contracts\Kernel\Notices;

interface SigninFactory extends ParamsBag
{
    /**
     * Résolution de sortie de la classe en tant que chaîne de caractère.
     * {@internal Affichage du formulaire d'authentification}
     *
     * @return string
     */
    public function __toString(): string;

    /**
     * Ajout d'un message de notification.
     *
     * @param string $type Type de notification. error|info.
     * @param string $message Intitulé du message de notification.
     * @param string $code Identifiant de qualification du message.
     * @param array $datas Liste des données associées.
     *
     * @return static
     */
    public function addNotice($type, $message = '', $code = null, $datas = []): SigninFactory;

    /**
     * Affichage du formulaire d'authentification.
     *
     * @return string
     */
    public function authForm();

    /**
     * Initialisation du formulaire d'authentification.
     *
     * @return void
     */
    public function boot(): void;

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
     * Récupération de la liste des messages de notification selon leur type.
     *
     * @param string $type Type de message. error|info.
     *
     * @return array
     */
    public function getMessages(string $type): array;

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
     * Affichage du lien de déconnection.
     *
     * @param array $attrs Liste des attributs de personnalisation.
     *
     * @return string
     */
    public function logoutLink($attrs = []): string;

    /**
     * Affichage du lien vers l'interface de récupération de mot de passe oublié.
     *
     * @return string
     */
    public function lostpasswordLink(): string;

    /**
     * Récupération de l'instance du gestionnaire de message de notification.
     *
     * @return Notices
     */
    public function notices(): Notices;

    /**
     * {@inheritDoc}
     *
     * @return SigninFactory
     */
    public function parse(): SigninFactory;

    /**
     * Initialisation de la classe.
     *
     * @param string $name Nom de qualification de l'interface d'authentification.
     * @param SigninManager $manager Instance du gestionnaire.
     *
     * @return SigninFactory
     */
    public function prepare(string $name, SigninManager $manager): SigninFactory;
}