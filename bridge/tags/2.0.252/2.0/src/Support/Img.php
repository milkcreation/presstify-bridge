<?php declare(strict_types=1);

namespace tiFy\Support;

class Img
{
    /**
     * Récupération de la source d'une image au format base64.
     *
     * @param string $filename Chemin absolu vers l'image
     *
     * @return string|null
     */
    public static function getBase64Src(string $filename): ?string
    {
        if (file_exists($filename)) {
            $mime_type = mime_content_type($filename);

            return sprintf(
                'data:%s;base64,%s',
                $mime_type !== 'image/svg' ? $mime_type : 'image/svg+xml',
                base64_encode(file_get_contents($filename))
            );
        }
        return null;
    }
}