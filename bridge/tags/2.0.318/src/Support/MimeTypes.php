<?php declare(strict_types=1);

namespace tiFy\Support;

use Closure;
use Mimey\MimeTypes as BaseMimeTypes;

class MimeTypes extends BaseMimeTypes
{
    /**
     * Cartographie des mime-types et extensions utilisées par défaut.
     * @var array|Closure|null
     */
    private static $defaultMapping;

    /**
     * Cartographie des mime-types et exentions autorisées.
     * @var array|Closure|null
     */
    private static $allowedMapping;

    /**
     * CONSTRUCTEUR.
     *
     * @param array|null $mapping
     *
     * @return void
     */
    public function __construct(?array $mapping = null)
    {
        if (is_null($mapping)) {
            $mapping = self::getDefaultMapping();
        }

        parent::__construct($mapping);
    }

    /**
     * Récupération de la cartographie extensions et mime-types autorisés.
     *
     * @return array|null
     */
    public static function getAllowedMapping(): ?array
    {
        if (self::$allowedMapping instanceof Closure) {
            return call_user_func(self::$allowedMapping);
        }

        return self::$allowedMapping;
    }

    /**
     * Récupération de la cartographie associée à une liste de termes (extensions|mime-types|types).
     *
     * @param string[] $terms Liste des extensions|mime-types|types.
     * @param bool $allowed Cartgographie basée sur les types autorisées uniquement.
     *
     * @return array[]
     */
    public static function getBuiltInMapping(array $terms, bool $allowed = false)
    {
        $extensions = [];
        $mimes = [];

        foreach ($terms as $term) {
            if ($mimeTypes = (new self($allowed ? self::getAllowedMapping() : null))
                    ->getMapping('mimes')[$term] ?? null) {
                $exts = [$term];
                foreach ($mimeTypes as $mimeType) {
                    if (!isset($extensions[$mimeType])) {
                        $extensions[$mimeType] = $exts;
                        foreach ($exts as $ext) {
                            if (!isset($mimes[$ext])) {
                                $mimes[$term] = [];
                            }
                            if (!in_array($mimeType, $mimes[$ext])) {
                                array_push($mimes[$ext], $mimeType);
                            }
                        }
                    }
                }
            } elseif (preg_match('/^(.*)\/(.*)$/', $term)) {
                $mimeType = $term;
                if (!isset($extensions[$mimeType])) {
                    $extensions[$mimeType] = $exts = (new self($allowed ? self::getAllowedMapping() : null))
                            ->getMapping('extensions')[$mimeType] ?? [];
                    foreach ($exts as $ext) {
                        if (!isset($mimes[$ext])) {
                            $mimes[$ext] = [];
                        }
                        if (!in_array($mimeType, $mimes[$ext])) {
                            array_push($mimes[$ext], $mimeType);
                        }
                    }
                }
            } elseif ($types = self::getTypeMimesExtensions($term, $allowed)) {
                array_walk($types, function ($exts, $mimeType) use (&$mimes, &$extensions) {
                    if (!isset($extensions[$mimeType])) {
                        $extensions[$mimeType] = $exts;
                        foreach ($exts as $ext) {
                            if (!isset($mimes[$ext])) {
                                $mimes[$ext] = [];
                            }
                            if (!in_array($mimeType, $mimes[$ext])) {
                                array_push($mimes[$ext], $mimeType);
                            }
                        }
                    }
                });
            }
        }

        return $extensions && $mimes ? compact('extensions', 'mimes') : [];
    }

    /**
     * Récupération de la cartographie extensions et mime-types par défaut.
     *
     * @return array|null
     */
    public static function getDefaultMapping(): ?array
    {
        if (self::$defaultMapping instanceof Closure) {
            return call_user_func(self::$defaultMapping);
        }

        return self::$defaultMapping;
    }

    /**
     * Récupération de la liste des mime-types et extensions relatives associée à un type.
     *
     * @param string $type ex. application|image|multipart|text|video|...
     * @param bool $allowed Cartgographie basée sur les types autorisées uniquement.
     *
     * @return array[]
     */
    public static function getTypeMimesExtensions(string $type, bool $allowed = false): array
    {
        $mimes = [];
        $extensions = (new self($allowed ? self::getAllowedMapping() : null))->getMapping('extensions');

        foreach ($extensions as $mimeType => $exts) {
            if (preg_match('/^' . $type . '\//', $mimeType)) {
                $mimes[$mimeType] = $exts;
            }
        }

        return $mimes;
    }

    /**
     * Vérifie si un fichier répond à un type-mime fichier ou une extension déclarée.
     *
     * @param string $filename Nom|Chemin relatif|Chemin absolu du fichier.
     * @param string[]|string|null $type Liste des types|mimeTypes|extensions à vérifier.
     *
     * @return bool
     */
    public static function inType(string $filename, $type = null): bool
    {
        $mapping = is_null($type) ? null : self::getBuiltInMapping(is_string($type) ? (array)$type : $type);

        if ($ext = pathinfo($filename, PATHINFO_EXTENSION)) {
            return !!(new self($mapping))->getMimeType($ext);
        }

        return false;
    }


    /**
     * Vérifie si un fichier
     *
     * @param string $filename Nom|Chemin relatif|Chemin absolu du fichier.
     * @param string[]|string|null $type Liste des types|mimeTypes|extensions à vérifier.
     *
     * @return bool
     */
    public static function inAllowedType(string $filename, $type = null): bool
    {
        $mapping = is_null($type)
            ? self::getAllowedMapping() : self::getBuiltInMapping(is_string($type) ? (array)$type : $type, true);

        if ($ext = pathinfo($filename, PATHINFO_EXTENSION)) {
            return !!(new self($mapping))->getMimeType($ext);
        }

        return false;
    }

    /**
     * Définition de la cartographie extensions et mime-types autorisées.
     *
     * @param array|Closure|null $allowed
     *
     * @return void
     */
    public static function setAllowedMapping($allowed): void
    {
        self::$allowedMapping = $allowed instanceof Closure || is_array($allowed) ? $allowed : null;
    }

    /**
     * Définition de la cartographie extensions et mime-types par défaut.
     *
     * @param array|Closure|null $default
     *
     * @return void
     */
    public static function setDefaultMapping($default): void
    {
        self::$defaultMapping = $default instanceof Closure || is_array($default) ? $default : null;
    }

    /**
     * Récupération de la cartographie pour l'entité mimes et/ou extensions.
     *
     * @param string $entity extensions|mimes
     *
     * @return array
     */
    public function getMapping(?string $entity = null): array
    {
        return $this->mapping[$entity] ?? $this->mapping;
    }
}