<?php declare(strict_types=1);

namespace tiFy\Support;

use Closure;
use Mimey\MimeTypes as BaseMimeTypes;

class MimeTypes extends BaseMimeTypes
{
    /**
     * Cartographie des mime-types et extensions utilisé par défaut.
     * @var array|Closure|null
     */
    private static $defaultMapping;

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
     * Récupération de la liste des mime-types et extensions relatives associée à un type.
     *
     * @param string $type ex. application|image|multipart|text|video|...
     *
     * @return array[]
     */
    public static function getTypeMimesExtensions(string $type): array
    {
        $mimes = [];
        foreach((new self())->getMapping('extensions') as $mimeType => $exts) {
            if (preg_match('/^' . $type .  '\//', $mimeType)) {
                $mimes[$mimeType] = $exts;
            }
        }

        return $mimes;
    }

    /**
     * Récupération de la cartographie associée à une liste de termes (extensions|mime-types|types).
     *
     * @param string[] $terms Liste des extensions|mime-types|types.
     *
     * @return array[]
     */
    public static function getBuiltInMapping(array $terms)
    {
        $extensions = [];
        $mimes = [];

        foreach($terms as $term) {
            if ($mimeTypes = (new self())->getMapping('mimes')[$term] ?? null) {
                $exts = [$term];
                foreach($mimeTypes as $mimeType) {
                    if (!isset($extensions[$mimeType])) {
                        $extensions[$mimeType] = $exts;
                        foreach($exts as $ext) {
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
                    $extensions[$mimeType] = $exts = (new self())->getMapping('extensions')[$mimeType] ?? [];
                    foreach($exts as $ext) {
                        if (!isset($mimes[$ext])) {
                            $mimes[$ext] = [];
                        }
                        if (!in_array($mimeType, $mimes[$ext])) {
                            array_push($mimes[$ext], $mimeType);
                        }
                    }
                }
            } elseif ($types = self::getTypeMimesExtensions($term)) {
                array_walk($types, function ($exts, $mimeType) use (&$mimes, &$extensions) {
                    if (!isset($extensions[$mimeType])) {
                        $extensions[$mimeType] = $exts;
                        foreach($exts as $ext) {
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
     * Vérifie si un fichier
     *
     * @param string Nom|Chemin relatif|Chemin absolu du fichier.
     * @param string[]|null $terms Liste des types|mimeTypes|extensions. Natifs par défaut.
     *
     * @return bool
     */
    public static function isAllowed(string $filename, ?array $terms = null): bool
    {
        if ($ext = pathinfo($filename, PATHINFO_EXTENSION)) {
            if ($terms) {
                $terms = self::getBuiltInMapping($terms);
            }
            return !!(new self($terms))->getMimeType($ext);
        }

        return false;
    }

    /**
     * Définition de la cartographie extensions et mime-types par défaut.
     *
     * @param array|Closure|null $default
     *
     * @return void
     */
    public static function setDefaultMapping($default)
    {
        self::$defaultMapping = $default instanceof Closure||is_array($default) ? $default : null;
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