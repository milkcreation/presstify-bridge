<?php declare(strict_types=1);

namespace tiFy\Contracts\Session;

use Illuminate\Database\Query\Builder as DbBuilder;
use tiFy\Contracts\Support\ParamsBag;

interface Store extends ParamsBag
{
    /**
     * @inheritDoc
     */
    public function db(): DbBuilder;

    /**
     * Destruction de la session.
     *
     * @return static
     */
    public function destroy(): Store;

    /**
     * Récupération de la prochaine date de définition d'expiration.
     *
     * @return int
     */
    public function expiration(): int;

    /**
     * Récupération d'attributs d'identification de session.
     *
     * @param string|array|null $keys Liste des attributs.
     * session_key|session_expiration|session_expiring|cookie_hash. Renvoi tout si vide.
     *
     * @return mixed
     */
    public function getCredentials($keys = null);

    /**
     * Récupération du hashage.
     *
     * @param int $expire Timestamp d'expiration du cookie
     *
     * @return string
     */
    public function getHash(int $expire): string;

    /**
     * Récupération de l'identifiant de qualification.
     *
     * @return string
     */
    public function getKey(): string;

    /**
     * Récupération du nom de qualification de la session
     *
     * @return string
     */
    public function getName(): string;

    /**
     * Récupération de la liste des variables de session enregistrés en base.
     *
     * @return array
     */
    public function getStored(): array;

    /**
     * Préparation de l'instance.
     *
     * @return static
     */
    public function prepare(): Store;

    /**
     * Définition d'une donnée de session.
     *
     * @param string $key Identifiant de qualification de la variable
     * @param mixed $value Valeur de la variable
     *
     * @return static
     */
    public function put(string $key, $value = null): Store;

    /**
     * Sauvegarde des données de session.
     *
     * @return static
     */
    public function save(): Store;

    /**
     * Récupération de l'instance du gestionnaire de session associé.
     *
     * @return Session
     */
    public function session(): Session;

    /**
     * Définition de l'indice de la clé de stockage des données de session.
     *
     * @param string|null $key
     *
     * @return static
     */
    public function setKey(?string $key = null): Store;

    /**
     * Définition du nom de qualification de la session.
     *
     * @param string $name
     *
     * @return static
     */
    public function setName(string $name): Store;

    /**
     * Mise à jour de la date d'expiration de la session en base.
     *
     * @param int $expiration Timestamp d'expiration de la session.
     *
     * @return static
     */
    public function updateStoredExpiration(int $expiration): Store;
}