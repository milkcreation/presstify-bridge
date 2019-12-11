<?php declare(strict_types=1);

namespace tiFy\Support;

use Carbon\Carbon;
use DateTime as BaseDateTime;
use DateTimeZone;
use Exception;
use tiFy\Contracts\Support\DateTime as DateTimeContract;
use tiFy\Support\Proxy\Request;

class DateTime extends Carbon implements DateTimeContract
{
    /**
     * Format de date par défaut
     * @var string
     */
    protected static $defaultFormat = 'Y-m-d H:i:s';

    /**
     * Instance du fuseau horaire utilisé par défaut.
     * @var DateTimeZone
     */
    protected static $globalTimeZone;

    /**
     * CONSTRUCTEUR.
     *
     * @param string|null $time
     * @param null|DateTimeZone $tz
     *
     * @return void
     *
     * @throws Exception
     */
    public function __construct($time = null, $tz = null)
    {
        if (is_null($tz)) {
            $tz = static::getGlobalTimeZone();
        }
        parent::__construct($time, $tz);
    }

    /**
     * {@inheritDoc}
     *
     * @return BaseDateTime|static|null
     */
    public static function createFromFormat($format, $time, $tz = null)
    {
        return parent::createFromFormat($format, $time, is_null($tz) ? static::getGlobalTimeZone() : $tz);
    }

    /**
     * @inheritDoc
     */
    public static function getGlobalTimeZone(): DateTimeZone
    {
        return static::$globalTimeZone ?: static::setGlobalTimeZone();
    }

    /**
     * @inheritDoc
     */
    public static function setDefaultFormat(string $format): string
    {
        return static::$defaultFormat = $format;
    }

    /**
     * @inheritDoc
     */
    public static function setGlobalTimeZone(?DateTimeZone $tz = null): DateTimeZone
    {
        return static::$globalTimeZone = $tz ?: new DateTimeZone(
            getenv('APP_TIMEZONE') ?: Request::server('TZ', ini_get('date.timezone') ?: 'UTC')
        );
    }

    /**
     * @inheritDoc
     */
    public function __toString(): string
    {
        return $this->format(static::$defaultFormat);
    }

    /**
     * @inheritDoc
     */
    public function local(?string $format = null): string
    {
        return $this->format($format ?: static::$defaultFormat);
    }

    /**
     * @inheritDoc
     */
    public function utc(?string $format = null): ?string
    {
        try {
            return (new static(null, 'UTC'))
                ->setTimestamp($this->getTimestamp())->format($format ?: static::$defaultFormat);
        } catch(Exception $e) {
            return null;
        }
    }
}