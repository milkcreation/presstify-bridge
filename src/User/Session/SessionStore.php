<?php

namespace tiFy\User\Session;

use Exception;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Cookie;
use tiFy\Contracts\User\SessionStore as SessionStoreContract;
use tiFy\Support\ParamsBag;

class SessionStore extends ParamsBag implements SessionStoreContract
{
    /**
     * Indicateur de modification des variables de session.
     * @var bool
     */
    protected $changed = false;

    /**
     * Identifiant de qualification du cookie de stockage de session.
     * @var string
     */
    protected $cookieName = '';

    /**
     * Listes des clés de qualification de session portés par le cookie.
     * @var string[]
     */
    protected $cookieKeys = [
        'session_key',
        'session_expiration',
        'session_expiring',
        'cookie_hash'
    ];

    /**
     * Nom de qualification de la session.
     * @var string
     */
    protected $name;

    /**
     * Liste des attributs de qualification de la session.
     * @var array
     */
    protected $session = [];

    /**
     * Liste des clés de qualification de session.
     * @var string[]
     */
    protected $sessionKeys = [
        'session_name',
        'session_key',
        'session_expiration',
        'session_expiring',
        'cookie_hash'
    ];

    /**
     * CONSTRUCTEUR
     *
     * @param string $name Identifiant de qualification de la session.
     * @param array $attrs Liste des attributs de configuration. @todo.
     *
     * @return void
     */
    public function __construct($name, $attrs = [])
    {
        $this->name = $name;

        add_action('init', function () {
            $this->cookieName = $this->getName() . "-" . COOKIEHASH;

            /**
             * @var array $cookie {
             *      Attribut de session contenu dans le cookie
             *
             *      @var string|int $session_key
             *      @var int $session_expiration
             *      @var int $session_expiring
             *      @var string $cookie_hash
             * }
             */
            if ($cookie = $this->getCookie()) :
                extract($cookie);

                if (time() > $session_expiring) :
                    $session_expiration = $this->nextSessionExpiration();
                    $this->updateDbExpiration($session_key, $session_expiration);
                endif;

                $this->attributes = $this->getDbDatas($session_key) ? : [];
            else :
                $session_key = $this->getKey();
                $session_expiration = $this->nextSessionExpiration();
            endif;

            $session_expiring = $this->nextSessionExpiring();
            $cookie_hash = $this->getCookieHash($session_key, $session_expiration);

            $this->session = array_merge(['session_name' => $this->getName()], compact($this->cookieKeys));
        });

        add_action('wp_loaded', function () {
            // Récupération des attributs de qualification de la session
            $session = $this->getSession($this->cookieKeys);

            // Définition du cookie
            $response = new Response();
            $response->headers->setCookie(
                new Cookie(
                    $this->getCookieName(),
                    rawurlencode(json_encode($session)),
                    time() + 3600,
                    ((COOKIEPATH != SITECOOKIEPATH) ? SITECOOKIEPATH : COOKIEPATH),
                    COOKIE_DOMAIN,
                    ('https' === parse_url(home_url(), PHP_URL_SCHEME))
                )
            );
            $response->send();
        }, 0);

        add_action('wp_logout', [$this, 'destroy']);

        add_action('shutdown', [$this, 'save']);
    }

    /**
     * @inheritdoc
     */
    public function clearCookie()
    {
        $response = new Response();
        $response->headers->clearCookie(
            $this->getCookieName(),
            ((COOKIEPATH != SITECOOKIEPATH) ? SITECOOKIEPATH : COOKIEPATH),
            COOKIE_DOMAIN,
            ('https' === parse_url(home_url(), PHP_URL_SCHEME))
        );
        $response->send();
    }

    /**
     * @inheritdoc
     */
    public function destroy()
    {
        // Suppression du cookie
        $this->clearCookie();

        // Suppression de la session en base
        $this->getDb()->handle()->delete(
            [
                'session_key' => $this->getSession('session_key'),
            ]
        );

        // Réinitialisation des variables de classe
        $this->session = [];
        $this->attributes = [];
        $this->changed = false;
    }

    /**
     * @inheritdoc
     */
    public function getCookie()
    {
        if (!$cookie = request()->cookie($this->getCookieName(), '')) :
            return false;
        elseif(!$cookie = (array)json_decode(rawurldecode($cookie), true)) :
            return false;
        elseif (array_diff(array_keys($cookie), $this->cookieKeys)) :
            return false;
        endif;

        /**
         * @var string|int $session_key
         * @var int $session_expiration
         * @var int $session_expiring
         * @var string $cookie_hash
         */
        extract($cookie);

        // Contrôle de validité du cookie
        $hash = $this->getCookieHash($session_key, $session_expiration);
        if (empty($cookie_hash) || !hash_equals($hash, $cookie_hash)) {
            return false;
        }

        return compact($this->cookieKeys);
    }

    /**
     * @inheritdoc
     */
    public function getCookieHash($session_key, $expiration)
    {
        $to_hash = $session_key . '|' . $expiration;

        return hash_hmac('md5', $to_hash, wp_hash($to_hash));
    }

    /**
     * @inheritdoc
     */
    public function getCookieName()
    {
        return $this->cookieName;
    }

    /**
     * @inheritdoc
     */
    public function getDb()
    {
        try {
            return user()->session()->getDb();
        } catch (Exception $e) {
            wp_die($e->getMessage(), __('ERREUR SYSTEME', 'tify'), $e->getCode());
            exit;
        }
    }

    /**
     * @inheritdoc
     */
    public function getDbDatas($session_key)
    {
        if (defined('WP_SETUP_CONFIG')) :
            return [];
        endif;

        if (
        $value = $this->getDb()->select()->cell(
            'session_value',
            [
                'session_name' => $this->getName(),
                'session_key'  => $session_key
            ]
        )
        ) :
            $value = array_map('maybe_unserialize', $value);
        endif;

        return $value;
    }

    /**
     * @inheritdoc
     */
    public function getKey()
    {
        return Str::random(32);
    }

    /**
     * @inheritdoc
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @inheritdoc
     */
    public function getSession($session_args = [])
    {
        // Récupération des attributs de qualification de la session
        if (!$session = $this->session) :
            return null;
        endif;
        extract($session);

        if (empty($session_args)) :
            $session_args = $this->sessionKeys;
        elseif (!is_array($session_args)) :
            $session_args = (array)$session_args;
        endif;

        // Limitation des attributs retournés à la liste des attributs autorisés
        $session_args = array_intersect($session_args, $this->sessionKeys);

        if (count($session_args) > 1) :
            return compact($session_args);
        else :
            return ${reset($session_args)};
        endif;
    }

    /**
     * @inheritdoc
     */
    public function nextSessionExpiration()
    {
        return time() + intval(60 * 60 * 48);
    }

    /**
     * @inheritdoc
     */
    public function nextSessionExpiring()
    {
        return time() + intval(60 * 60 * 47);
    }

    /**
     * @inheritdoc
     */
    public function put($key, $value = null)
    {
        if ($value !== $this->get($key)) {
            if (!is_array($key)) {
                $key = [$key => $value];
            }

            foreach ($key as $arrayKey => $arrayValue) {
                Arr::set($this->attributes, $arrayKey, $arrayValue);
            }

            $this->changed = true;
        }

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function save()
    {
        if ($this->changed) :
            // Récupération des attributs de session
            $session = $this->getSession();

            $this->getDb()->handle()->replace([
                'session_name'   => $session['session_name'],
                'session_key'    => $session['session_key'],
                'session_value'  => maybe_serialize($this->attributes),
                'session_expiry' => $session['session_expiration']
            ], ['%s', '%s', '%s', '%d']);

            $this->changed = false;
        endif;
    }

    /**
     * @inheritdoc
     */
    public function updateDbExpiration($session_key, $expiration)
    {
        $this->getDb()->sql()->update(
            $this->getDb()->getName(),
            [
                'session_expiry' => $expiration
            ],
            [
                'session_name' => $this->getName(),
                'session_key'  => $session_key
            ]
        );
    }
}