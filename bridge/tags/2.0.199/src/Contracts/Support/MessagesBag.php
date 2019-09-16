<?php declare(strict_types=1);

namespace tiFy\Contracts\Support;

use ArrayAccess;
use Countable;
use IteratorAggregate;
use JsonSerializable;

interface MessagesBag extends ArrayAccess, Countable, IteratorAggregate, JsonSerializable
{
    /**
     * @inheritDoc
     */
    public static function convertLevel(string $level): int;

    /**
     * @inheritDoc
     */
    public static function getLevelName(int $level): string;

    /**
     * Ajout d'un message de notification.
     *
     * @param string|int $level Niveau de notification.
     * @param string $message Intitulé du message
     * @param array|null $datas Liste des données associées.
     * @param string|null $code Code de qualification.
     *
     * @return string|null Clé d'indice de qualification|null si l'ajout est en échec.
     */
    public function add($level, string $message = '', ?array $datas = null, ?string $code = null): ?string;

    /**
     * Ajout d'un message d'alerte.
     *
     * @param string $message Intitulé du message
     * @param array|null $datas Liste des données associées.
     * @param string|null $code Code de qualification.
     *
     * @return string|null Clé d'indice de qualification|null si l'ajout est en échec.
     */
    public function alert(string $message = '', ?array $datas = null, ?string $code = null): ?string;

    /**
     * Récupération de la liste complète des messages ou uniquement ceux associés à un type de notification.
     *
     * @param int $level Niveau de notification.
     *
     * @return array
     */
    public function all(?int $level = null): array;

    /**
     * Récupération de la liste complète des code de qualification ou ceux correspondant à un type de notification.
     *
     * @param int|null $level Niveau de notification.
     *
     * @return string[]
     */
    public function code(?int $level = null): array;

    /**
     * Détermine le nombre de messages ou ceux associés à un type de notification.
     *
     * @param int|null $level Niveau de notification.
     *
     * @return int
     */
    public function count(?int $level = null): int;

    /**
     * Ajout d'un message de condition critique.
     *
     * @param string $message Intitulé du message
     * @param array|null $datas Liste des données associées.
     * @param string|null $code Code de qualification.
     *
     * @return string|null Clé d'indice de qualification|null si l'ajout est en échec.
     */
    public function critical(string $message = '', ?array $datas = null, ?string $code = null): ?string;

    /**
     * Récupération de la liste complète des données associées ou celles correspondant à un type de notification.
     *
     * @param int $level Niveau de notification.
     * @param string|null $code Code de qualification.
     * @param mixed $default $default Valeur de retour par defaut.
     *
     * @return mixed
     */
    public function datas(int $level, $code = null, $default = null);

    /**
     * Ajout d'un message de deboguage.
     *
     * @param string $message Intitulé du message
     * @param array|null $datas Liste des données associées.
     * @param string|null $code Code de qualification.
     *
     * @return string|null Clé d'indice de qualification|null si l'ajout est en échec.
     */
    public function debug(string $message = '', ?array $datas = null, ?string $code = null): ?string;

    /**
     * Ajout d'un message d'urgence.
     *
     * @param string $message Intitulé du message
     * @param array|null $datas Liste des données associées.
     * @param string|null $code Code de qualification.
     *
     * @return string|null Clé d'indice de qualification|null si l'ajout est en échec.
     */
    public function emergency(string $message = '', ?array $datas = null, ?string $code = null): ?string;

    /**
     * Ajout d'un message d'erreur.
     *
     * @param string $message Intitulé du message
     * @param array|null $datas Liste des données associées.
     * @param string|null $code Code de qualification.
     *
     * @return string|null Clé d'indice de qualification|null si l'ajout est en échec.
     */
    public function error(string $message = '', ?array $datas = null, ?string $code = null): ?string;

    /**
     * Vérification d'existance de messages ou seulement ceux associés à un type de notification.
     *
     * @param int|null $level Niveau de notification.
     *
     * @return boolean
     */
    public function exists(?int $level = null): bool;

    /**
     * Retrouve la liste des messages et les données relatives associées à un type de notification et possiblement un
     * indice de qualification.
     *
     * @param int|null $level Niveau de notification.
     * @param string|null $code Code de qualification.
     *
     * @return array
     */
    public function fetch(?int $level = null, $code = null): array;

    /**
     * Réinitialisation de la liste complète des messages ou ceux associés à un type de notification.
     *
     * @param int|null $level Niveau de notification.
     *
     * @return static
     */
    public function flush(?int $level = null): MessagesBag;

    /**
     * Récupération de la liste complète des messages ou ceux correspondant à un type de notification.
     *
     * @param int $level Niveau de notification.
     * @param string|null $code Code de qualification.
     * @param mixed $default $default Valeur de retour par defaut.
     *
     * @return mixed
     */
    public function get(int $level, $code = null, string $default = '');

    /**
     * Vérification d'existance d'un niveau de notification déclaré.
     *
     * @param int $level Niveau de notification.
     *
     * @return boolean
     */
    public function hasLevel(int $level): bool;

    /**
     * Ajout d'un message d'information.
     *
     * @param string $message Intitulé du message
     * @param array|null $datas Liste des données associées.
     * @param string|null $code Code de qualification.
     *
     * @return string Clé d'indice de qualification.
     */
    public function info(string $message = '', ?array $datas = null, ?string $code = null): ?string;

    /**
     * Récupération de la liste des paramètres au format json.
     * @see http://php.net/manual/fr/function.json-encode.php
     *
     * @param int $options Options d'encodage.
     *
     * @return string
     */
    public function json($options = 0);

    /**
     * Récupération de la liste des niveaux de notification déclarés.
     *
     * @return array
     */
    public function levels(): array;

    /**
     * Ajout d'un message de niveau arbitraire.
     *
     * @param mixed $level
     * @param string $message Intitulé du message
     * @param array|null $datas Liste des données associées.
     * @param string|null $code Code de qualification.
     *
     * @return string|null Clé d'indice de qualification|null si l'ajout est en échec.
     */
    public function log($level, string $message = '', ?array $datas = null, ?string $code = null): ?string;

    /**
     * Récupération de la liste complète des messages ou ceux correspondant à un type de notification.
     *
     * @param int $level Niveau de notification.
     * @param string|null $code Code de qualification.
     * @param string $default $default Valeur de retour par defaut.
     *
     * @return mixed
     */
    public function messages(int $level, $code = null, string $default = '');

    /**
     * Ajout d'un message de notification.
     *
     * @param string $message Intitulé du message
     * @param array|null $datas Liste des données associées.
     * @param string|null $code Code de qualification.
     *
     * @return string|null Clé d'indice de qualification|null si l'ajout est en échec.
     */
    public function notice(string $message = '', ?array $datas = null, ?string $code = null): ?string;

    /**
     * Définition du niveau de notification de récupération des messages.
     *
     * @param string|int $level
     *
     * @return static
     */
    public function setLevel($level): MessagesBag;

    /**
     * Ajout d'un message de succès.
     *
     * @param string $message Intitulé du message
     * @param array|null $datas Liste des données associées.
     * @param string|null $code Code de qualification.
     *
     * @return string|null Clé d'indice de qualification|null si l'ajout est en échec.
     */
    public function success(string $message = '', ?array $datas = null, ?string $code = null): ?string;

    /**
     * Ajout d'un message d'avertissement.
     *
     * @param string $message Intitulé du message
     * @param array|null $datas Liste des données associées.
     * @param string|null $code Code de qualification.
     *
     * @return string|null Clé d'indice de qualification|null si l'ajout est en échec.
     */
    public function warning(string $message = '', ?array $datas = null, ?string $code = null): ?string;
}
