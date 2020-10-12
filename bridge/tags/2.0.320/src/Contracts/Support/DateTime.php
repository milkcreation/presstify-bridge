<?php declare(strict_types=1);

namespace tiFy\Contracts\Support;

use Carbon\Carbon;
use DateTime as BaseDateTime;
use DateTimeZone;

/**
 * @mixin BaseDateTime
 * @mixin Carbon
 */
interface DateTime
{
    /**
     * {@inheritDoc}
     *
     * @return BaseDateTime|static|null
     */
    public static function createFromFormat($format, $time, $tz=null);

    /**
     * Récupération du fuseau horaire par défaut.
     *
     * @return DateTimeZone
     */
    public static function getGlobalTimeZone(): DateTimeZone;

    /**
     * Définition du format d'affichage par défault de la date.
     *
     * @param string $format
     *
     * @return string
     */
    public static function setDefaultFormat(string $format): string;

    /**
     * Définition du fuseau horaire par défaut.
     *
     * @param DateTimeZone|null $tz
     *
     * @return DateTimeZone
     */
    public static function setGlobalTimeZone(?DateTimeZone $tz = null): DateTimeZone;

    /**
     * Récupération de la date locale pour un format donné.
     *
     * @param string|null $format Format d'affichage de la date. MySQL par défaut.
     *
     * @return string
     */
    public function local(?string $format = null): string;

    /**
     * Récupération de la date basée sur le temps universel pour un format donné.
     *
     * @param string|null $format Format d'affichage de la date. MySQL par défaut.
     *
     * @return string|null
     */
    public function utc(?string $format = null): ?string;
}