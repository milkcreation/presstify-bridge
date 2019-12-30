<?php declare(strict_types=1);

namespace tiFy\Session;

use Illuminate\Database\Query\Builder as DbBuilder;
use tiFy\Contracts\Session\{Session, Store as StoreContract};
use tiFy\Support\{Arr, ParamsBag, Str};
use tiFy\Support\Proxy\{Crypt, Database, Log};

class Store extends ParamsBag implements StoreContract
{
    /**
     * Indicateur de modification des variables de session.
     * @var boolean
     */
    protected $changed = false;

    /**
     * Liste des attributs d'identification de stockage de session.
     * @var array
     */
    protected $credentials = [];

    /**
     * Listes des clés de qualification des attributs d'identification de stockage de session.
     * @var string[]
     */
    protected $credentialKeys = ['key', 'expiration', 'hash'];

    /**
     * Clé d'indice de stockage de session.
     * @var string
     */
    protected $key = '';

    /**
     * Nom de qualification de la session.
     * @var string
     */
    protected $name = '';

    /**
     * Indicateur d'initialisation de l'instance.
     * @var boolean
     */
    private $prepared = false;

    /**
     * Instance du gestionnaire de sessions.
     * @var Session
     */
    protected $session;

    protected $log;

    /**
     * CONSTRUCTEUR
     *
     * @param Session $session Instance du gestionnaire de session.
     *
     * @return void
     */
    public function __construct(Session $session)
    {
        $this->session = $session;
    }

    /**
     * @inheritDoc
     */
    public function db(): DbBuilder
    {
        return Database::table('tify_session');
    }

    /**
     * @inheritDoc
     */
    public function destroy(): StoreContract
    {
        $this->session()->set($this->getName(), null);

        $this->db()->where([
            'session_key'  => $this->getKey(),
            'session_name' => $this->getName(),
        ])->delete();

        $this->attributes = [];
        $this->changed = false;
        $this->credentials = [];

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function expiration(): int
    {
        return time() + intval(60 * 60 * 48);
    }

    /**
     * @inheritDoc
     */
    public function getCredentials($keys = null): ?array
    {
        if (!$credentials = $this->credentials) {
            return null;
        }

        if (is_null($keys)) {
            return $credentials;
        } elseif (is_string($keys)) {
            $keys = [$keys];
        } elseif (!is_array($keys)) {
            return null;
        }

        $keys = array_intersect($keys, $this->credentialKeys);

        return count($keys) > 1 ? compact($keys) : ($credentials[reset($keys)] ?? null);
    }

    /**
     * @inheritDoc
     */
    public function getHash(int $expire): string
    {
        $hash = $this->getKey() . '|' . $expire;

        return hash_hmac('md5', $hash, Crypt::encrypt($hash));
    }

    /**
     * @inheritDoc
     */
    public function getKey(): string
    {
        return $this->key;
    }

    /**
     * @inheritDoc
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @inheritDoc
     */
    public function getStored(): array
    {
        $value = $this->db()->where([
            'session_key'  => $this->getKey(),
            'session_name' => $this->getName(),
        ])->value('session_value');

        $value = is_string($value) ? Str::unserialize($value) : [];

        return is_array($value) ? $value : [];
    }

    /**
     * @inheritDoc
     */
    public function prepare(): StoreContract
    {
        if (!$this->prepared) {
            register_shutdown_function([$this, 'save']);

            if ($credentials = $this->session()->get($this->getName())) {
                /**
                 * @var string|int $key
                 * @var int $expiration
                 * @var string $hash
                 */
                extract($credentials);

                $this->setKey($key);

                if (time() > $expiration) {
                    $expiration = $this->expiration();
                    $this->updateStoredExpiration($expiration);
                }

                $this->set($this->getStored() ?: []);
            } else {
                $this->setKey();
                $expiration = $this->expiration();
            }

            $this->credentials = array_merge(compact('expiration'), [
                'hash' => $this->getHash($expiration),
                'key'  => $this->getKey(),
            ]);

            $this->session()->set($this->getName(), $this->credentials);

            $this->prepared = true;
        }

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function put(string $key, $value = null): StoreContract
    {
        if ($value !== $this->get($key)) {
            $this->set($key, $value);

            $this->changed = true;
        }

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function save(): StoreContract
    {
        if ($this->changed) {
            $this->db()->updateOrInsert([
                'session_key'  => $this->getKey(),
                'session_name' => $this->getName(),
            ], [
                'session_value'  => Arr::serialize($this->all()),
                'session_expiry' => $this->expiration(),
            ]);

            Log::registerChannel('session', [
                'filename' => WP_CONTENT_DIR . '/uploads/log/session.log',
            ])->info('prepare', [
                'id'     => spl_object_hash($this),
                'key'    => $this->getKey(),
                'name'   => $this->getName(),
                'expiry' => $this->expiration(),
                'value'  => Arr::serialize($this->all()),
            ]);

            $this->changed = false;
        }

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function session(): Session
    {
        return $this->session;
    }

    /**
     * @inheritDoc
     */
    public function setKey(?string $key = null): StoreContract
    {
        $this->key = is_null($key) ? Str::random(32) : $key;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setName(string $name): StoreContract
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function updateStoredExpiration(int $expiration): StoreContract
    {
        $this->db()->where([
            'session_key'  => $this->getKey(),
            'session_name' => $this->getName(),
        ])->update(['session_expiry' => $expiration]);

        return $this;
    }
}