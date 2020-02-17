<?php declare(strict_types=1);

namespace tiFy\Contracts\Cookie;

use Psr\Container\ContainerInterface as Container;
use Symfony\Component\HttpFoundation\Cookie as SfCookie;
use tiFy\Contracts\Http\Response;

interface Cookie
{
    /**
     * Suppression du cookie.
     *
     * @return Response
     */
    public function clear(): Response;

    /**
     * Génération du cookie.
     *
     * @param string|array|null $value Valeur du cookie à définir.
     * @param array ...$args {
     *      Liste dynamique d'arguments complémentaires de définition du cookie.
     *
     *      @var int $expire
     *      @var string|null $path
     *      @var string|null $domain
     *      @var boolean $secure
     *      @var boolean $httpOnly
     *      @var boolean $raw
     *      @var string|null $sameSite
     * }
     *
     * @return SfCookie
     */
    public function generate($value = null, ...$args): SfCookie;

    /**
     * Récupération de la valeur d'un cookie.
     *
     * @param string|null $key Clé d'indice de la valeur. La valeur doit être un tableau. Syntaxe à point permise.
     * @param mixed $default Valeur de retour par défaut.
     *
     * @return mixed
     */
    public function get(?string $key = null, $default = null);

    /**
     * Récupération du conteneur d'injection de dépendances.
     *
     * @return Container|null
     */
    public function getContainer(): ?Container;

    /**
     * Récupération du nom de qualification du domaine du site associé.
     *
     * @return string|null
     */
    public function getDomain(): ?string;

    /**
     * Récupération du nom de qualification d'un cookie.
     *
     * @return string
     */
    public function getName(): string;

    /**
     * Récupération du chemin de validité des cookies.
     *
     * @return static
     */
    public function getPath(): ?string;

    /**
     * Création ou récupération d'une instance.
     *
     * @param string $alias Alias de qualification de l'instance.
     * @param string|array|null $attrs Nom de qualification lorsque celui diffère de l'alias|attributs de configuration.
     *
     * @return static
     */
    public function instance(string $alias, $attrs = null): Cookie;

    /**
     * Récupération de la liste des arguments.
     *
     * @param array ...$args {
     *      Liste dynamique d'arguments de définition du cookie.
     *
     *      @var string|array|null $value
     *      @var int $expire
     *      @var string|null $path
     *      @var string|null $domain
     *      @var boolean $secure
     *      @var boolean $httpOnly
     *      @var boolean $raw
     *      @var string|null $sameSite
     * }
     *
     * @return array
     */
    public function parseArgs(...$args): array;

    /**
     * Définition du cookie.
     *
     * @param string|array|null $value Valeur du cookie à définir.
     * @param array ...$args {
     *      Liste dynamique d'arguments complémentaires de définition du cookie.
     *
     *      @var int $expire
     *      @var string|null $path
     *      @var string|null $domain
     *      @var boolean $secure
     *      @var boolean $httpOnly
     *      @var boolean $raw
     *      @var string|null $sameSite
     * }
     *
     * @return Response
     */
    public function set($value = null, ...$args): Response;

    /**
     * Définition de l'activation de l'encodage en base64 de la valeurs des cookies.
     *
     * @param boolean $active
     *
     * @return static
     */
    public function setBase64(bool $active = false): Cookie;

    /**
     * Définition du nom de qualification du domaine du site associé.
     *
     * @param string|null $domain
     *
     * @return static
     */
    public function setDomain(?string $domain = null): Cookie;

    /**
     * Définition du nom de qualification du cookie.
     *
     * @param string $name
     *
     * @return static
     */
    public function setName(string $name): Cookie;

    /**
     * Définition du chemin de validité des cookies.
     *
     * @param string|null $path
     *
     * @return static
     */
    public function setPath(?string $path = null): Cookie;

    /**
     * Définition du suffixe de Salage du nom de qualification des cookies.
     *
     * @param string $salt
     *
     * @return static
     */
    public function setSalt(string $salt = ''): Cookie;

    /**
     * Définition de la liste des arguments par défaut.
     *
     * @param string|array|null $value
     * @param int $expire
     * @param string|null $path
     * @param string|null $domain
     * @param boolean|null $secure
     * @param boolean $httpOnly
     * @param boolean $raw
     * @param string|null $sameSite
     *
     * @return static
     */
    public function setDefaults(
        $value = null,
        int $expire = 0,
        ?string $path = '/',
        ?string $domain = null,
        ?bool $secure = null,
        bool $httpOnly = true,
        bool $raw = false,
        ?string $sameSite = null
    ): Cookie;
}

