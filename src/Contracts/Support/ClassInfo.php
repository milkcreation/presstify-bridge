<?php declare(strict_types=1);

namespace tiFy\Contracts\Support;

use ReflectionClass;

/**
 * @mixin ReflectionClass
 */
interface ClassInfo
{
    /**
     * Délégation d'appel d'une méthode de ReflectionClass.
     *
     * @param string $name Nom de la méthode à appeler.
     * @param array $arguments Liste des variables passées en argument.
     *
     * @return mixed
     */
    public function __call($name, $arguments);

    /**
     * Récupération du chemin absolu vers le repertoire de stockage d'une application déclarée.
     *
     * @return string
     */
    public function getDirname(): string;

    /**
     * Récupération du nom court de la classe au format kebab (Minuscules séparées par des tirets).
     *
     * @return string
     */
    public function getKebabName(): string;

    /**
     * Récupération du chemin relatif vers le repertoire de stockage d'une application déclarée.
     * @internal Basé sur le chemin absolu de la racine du proje
     * 
     * @return string
     */
    public function getRelPath(): string;

    /**
     * Récupération de l'url vers le repertoire de stockage d'une application déclarée.
     *
     * @return string
     */
    public function getUrl(): string;
}