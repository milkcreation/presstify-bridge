<?php declare(strict_types=1);

namespace tiFy\Contracts\Filesystem;

interface ImgFilesystem extends LocalFilesystem
{
    /**
     * Récupération de la source d'une image encodée en base64.
     *
     * @param string $path Chemin relatif vers la ressource image.
     *
     * @return string|null
     */
    public function src(string $path): ?string;

    /**
     * Récupération du rendu d'affichage d'une image.
     *
     * @param string $path Chemin relatif vers la ressource image.
     * @param array|null $attrs Liste des attributs de balise HTML. Si null, pas d'encapsulation (.svg uniquement).
     *
     * @return string
     */
    public function render(string $path, ?array $attrs = []): ?string;
}