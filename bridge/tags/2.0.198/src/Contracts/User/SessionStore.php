<?php

namespace tiFy\Contracts\User;

use tiFy\Contracts\Db\DbFactory;
use tiFy\Contracts\Support\ParamsBag;

interface SessionStore extends ParamsBag
{
    /**
     * Suppression du cookie de session
     *
     * @return void
     */
    public function clearCookie();

    /**
     * Destruction de la session.
     *
     * @return void
     */
    public function destroy();

    /**
     * Récupération du cookie de session
     *
     * @return mixed
     */
    public function getCookie();

    /**
     * Récupération du hashage de cookie
     *
     * @param int|string $session_key Identifiant de qualification de l'utilisateur courant
     * @param int $expiration Timestamp d'expiration du cookie
     *
     * @return string
     */
    public function getCookieHash($session_key, $expiration);

    /**
     * Récupération du nom de qualification du cookie d'enregistrement de correspondance de session
     *
     * @return string
     */
    public function getCookieName();

    /**
     * Récupération de la classe de rappel de la table de base de données
     *
     * @return DbFactory
     */
    public function getDb();

    /**
     * Récupération de la liste des variables de session enregistrés en base.
     *
     * @param mixed $session_key Clé de qualification de la session
     *
     * @return array
     */
    public function getDbDatas($session_key);

    /**
     * Récupération de l'identifiant de qualification.
     *
     * @return string
     */
    public function getKey();

    /**
     * Récupération du nom de qualification de la session
     *
     * @return string
     */
    public function getName();

    /**
     * Récupération d'un ou plusieurs ou tous les attributs de qualification de la session.
     *
     * @param array $session_args Liste des attributs de retour.
     * session_key|session_expiration|session_expiring|cookie_hash. Renvoi tout si vide.
     *
     * @return mixed
     */
    public function getSession($session_args = []);

    /**
     * Récupération de la prochaine date de définition d'expiration de session
     *
     * @return int
     */
    public function nextSessionExpiration();

    /**
     * Récupération de la prochaine date de définition de session expirée
     *
     * @return int
     */
    public function nextSessionExpiring();

    /**
     * Définition d'une donnée de session.
     *
     * @param string $key Identifiant de qualification de la variable
     * @param mixed $value Valeur de la variable
     *
     * @return $this
     */
    public function put($key, $value = null);

    /**
     * Sauvegarde des données de session.
     *
     * @return void
     */
    public function save();

    /**
     * Mise à jour de la date d'expiration de la session en base.
     *
     * @param mixed $session_key Clé de qualification de la session
     * @param string $expiration Timestamp d'expiration de la session
     *
     * @return void
     */
    public function updateDbExpiration($session_key, $expiration);
}