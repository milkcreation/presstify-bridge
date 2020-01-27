<?php declare(strict_types=1);

namespace tiFy\Support;

use ArrayIterator;
use tiFy\Contracts\Support\MessagesBag as MessagesBagContract;

/**
 * @see https://fr.wikipedia.org/wiki/Syslog
 */
class MessagesBag implements MessagesBagContract
{
    /**
     * Niveau de notification de débogguage.
     * @var int
     */
    public const DEBUG = 100;

    /**
     * Niveau de notification d'information.
     * @var int
     */
    public const INFO = 200;

    /**
     * Niveau de notification d'événement normal méritant d'être signalé.
     * @var int
     */
    public const NOTICE = 250;

    /**
     * Niveau de notification d'avertissement (une erreur peut intervenir si aucune action n'est prise).
     * @var int
     */
    public const WARNING = 300;

    /**
     * Niveau de notification d'erreur de fonctionnement.
     * @var int
     */
    public const ERROR = 400;

    /**
     * Niveau de notification d'erreur critique pour le système.
     * @var int
     */
    public const CRITICAL = 500;

    /**
     * Niveau de notification d'intervention immédiate nécessaire.
     * @var int
     */
    public const ALERT = 550;

    /**
     * Niveau de notification de système inutilisable.
     * @var int
     */
    public const EMERGENCY = 600;

    /**
     * Logging levels from syslog protocol defined in RFC 5424
     *
     * @var array $levels Logging levels
     */
    protected static $levels = [
        self::ALERT     => 'ALERT',
        self::CRITICAL  => 'CRITICAL',
        self::DEBUG     => 'DEBUG',
        self::EMERGENCY => 'EMERGENCY',
        self::ERROR     => 'ERROR',
        self::INFO      => 'INFO',
        self::NOTICE    => 'NOTICE',
        self::WARNING   => 'WARNING',
    ];

    /**
     * Instance des données associées aux messages.
     * @var ParamsBag
     */
    protected $datas;

    /**
     * Niveau de notification de récupération des messages.
     * @var int
     */
    protected $level = self::DEBUG;

    /**
     * Instance des messages.
     * @var ParamsBag
     */
    protected $messages;

    /**
     * CONSTRUCTEUR.
     *
     * @return void
     */
    public function __construct()
    {
        $this->datas = new ParamsBag();
        $this->messages = new ParamsBag();
    }

    /**
     * @inheritDoc
     */
    public static function convertLevel($level): int
    {
        if (is_string($level) && defined(__CLASS__ . '::' . strtoupper($level))) {
            return constant(__CLASS__ . '::' . strtoupper($level));
        }

        return is_int($level) ? $level : self::DEBUG;
    }

    /**
     * @inheritDoc
     */
    public static function getLevelName(int $level): string
    {
        return static::$levels[$level] ?? 'DEBUG';
    }

    /**
     * Récupération d'un élément d'itération.
     *
     * @param int $level Niveau de notification.
     *
     * @return mixed
     */
    public function __get(int $level)
    {
        return $this->get($level);
    }

    /**
     * Définition d'un élément d'itération.
     *
     * @param int $level Niveau de notification.
     * @param mixed $value Valeur.
     *
     * @return void
     */
    public function __set(int $level, $value)
    {
        $this->offsetSet($level, $value);
    }

    /**
     * Vérification d'existance d'un élément d'itération.
     *
     * @param int $level Niveau de notification.
     *
     * @return bool
     */
    public function __isset(int $level)
    {
        return $this->offsetExists($level);
    }

    /**
     * Suppression d'un élément d'itération.
     *
     * @param int $level Niveau de notification.
     *
     * @return void
     */
    public function __unset(int $level)
    {
        $this->offsetUnset($level);
    }

    /**
     * @inheritDoc
     */
    public function add($level, string $message = '', ?array $datas = null, ?string $code = null): ?string
    {
        $level = self::convertLevel($level);

        $code = $code ?: Str::random();
        $this->messages->set("{$level}.{$code}", $message);
        if ($datas) {
            $this->datas->set("{$level}.{$code}", $datas);
        }

        return $code;
    }

    /**
     * @inheritDoc
     */
    public function alert(string $message = '', ?array $datas = null, ?string $code = null): ?string
    {
        return $this->add(self::ALERT, $message, $datas, $code);
    }

    /**
     * @inheritDoc
     */
    public function all(?int $level = null): array
    {
        return is_null($level) ? $this->messages->all() : $this->get($level, []);
    }

    /**
     * @inheritDoc
     */
    public function count(?int $level = null): int
    {
        $count = 0;
        if (is_null($level)) {
            foreach ($this->levels() as $level) {
                $count += $this->count($level);
            }
            return $count;
        } else {
            return count($this->messages->get($level, []));
        }
    }

    /**
     * @inheritDoc
     */
    public function critical(string $message = '', ?array $datas = null, ?string $code = null): ?string
    {
        return $this->add(self::CRITICAL, $message, $datas, $code);
    }

    /**
     * @inheritDoc
     */
    public function datas(int $level, $code = null, $default = null)
    {
        if (is_null($code)) {
            return $this->datas->get($level, []);
        } else {
            return $this->datas->get("{$level}.{$code}", $default);
        }
    }

    /**
     * @inheritDoc
     */
    public function debug(string $message = '', ?array $datas = null, ?string $code = null): ?string
    {
        return $this->add(self::DEBUG, $message, $datas, $code);
    }

    /**
     * @inheritDoc
     */
    public function emergency(string $message = '', ?array $datas = null, ?string $code = null): ?string
    {
        return $this->add(self::EMERGENCY, $message, $datas, $code);
    }

    /**
     * @inheritDoc
     */
    public function error(string $message = '', ?array $datas = null, ?string $code = null): ?string
    {
        return $this->add(self::ERROR, $message, $datas, $code);
    }

    /**
     * @inheritDoc
     */
    public function exists(?int $level = null): bool
    {
        return $this->count($level) > 0;
    }

    /**
     * @inheritDoc
     */
    public function fetch(?int $level = null, $code = null): array
    {
        $items = [];

        if (is_null($level)) {
            foreach ($this->levels() as $level) {
                $items += $this->fetch($level);
            }
        } elseif (is_null($code)) {
            foreach ($this->code($level) as $code) {
                $message = $this->messages($level, $code, '');
                $data = $this->datas($level, $code, []);
                $items[] = compact('code', 'data', 'level', 'message');
            }
        } else {
            $message = $this->messages($level, $code, '');
            $data = $this->datas($level, $code, []);
            $items[] = compact('code', 'data', 'level', 'message');
        }

        return $items;
    }

    /**
     * @inheritDoc
     */
    public function flush(?int $level = null): MessagesBagContract
    {
        $this->messages->forget($level ? $level : $this->levels());
        $this->datas->forget($level ? $level : $this->levels());

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function get(int $level, $code = null, string $default = '')
    {
        if (is_null($code)) {
            return $this->messages->get($level, []);
        } else {
            return $this->messages->get("{$level}.{$code}", $default);
        }
    }

    /**
     * @inheritDoc
     */
    public function getIterator()
    {
        return new ArrayIterator($this->all());
    }

    /**
     * @inheritDoc
     */
    public function hasLevel(int $level): bool
    {
        return in_array($level, $this->levels());
    }

    /**
     * @inheritDoc
     */
    public function info(string $message = '', ?array $datas = null, ?string $code = null): ?string
    {
        return $this->add(self::INFO, $message, $datas, $code);
    }

    /**
     * @inheritDoc
     */
    public function json($options = 0)
    {
        return json_encode($this->all(), $options);
    }

    /**
     * @inheritDoc
     */
    public function jsonSerialize()
    {
        return $this->all();
    }

    /**
     * @inheritDoc
     */
    public function code(?int $level = null): array
    {
        if ($level) {
            return array_keys($this->messages->get($level));
        } else {
            $codes = [];
            foreach ($this->levels() as $level) {
                $codes += $this->code($level);
            }
            return $codes;
        }
    }

    /**
     * @inheritDoc
     */
    public function levels(): array
    {
        return $this->messages->keys() ?: [];
    }

    /**
     * @inheritDoc
     */
    public function log($level, string $message = '', ?array $datas = null, ?string $code = null): ?string
    {
        return $this->add($level, $message, $datas, $code);
    }

    /**
     * @inheritDoc
     */
    public function messages(int $level, $code = null, string $default = '')
    {
        return $this->get($level, $code, $default);
    }

    /**
     * @inheritDoc
     */
    public function notice(string $message = '', ?array $datas = null, ?string $code = null): ?string
    {
        return $this->add(self::NOTICE, $message, $datas, $code);
    }

    /**
     * @inheritDoc
     */
    public function offsetExists($offset)
    {
        return isset($this->messages[$offset]);
    }

    /**
     * @inheritDoc
     */
    public function offsetGet($offset)
    {
        return $this->get($offset);
    }

    /**
     * @inheritDoc
     */
    public function offsetSet($offset, $value)
    {
        $this->messages[$offset] = $value;
    }

    /**
     * @inheritDoc
     */
    public function offsetUnset($offset)
    {
        unset($this->messages[$offset]);
    }

    /**
     * @inheritDoc
     */
    public function setLevel($level): MessagesBagContract
    {
        $this->level = $level;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function success(string $message = '', ?array $datas = null, ?string $code = null): ?string
    {
        return $this->add(self::NOTICE, $message, $datas, $code);
    }

    /**
     * @inheritDoc
     */
    public function warning(string $message = '', ?array $datas = null, ?string $code = null): ?string
    {
        return $this->add(static::WARNING, $message, $datas, $code);
    }
}
