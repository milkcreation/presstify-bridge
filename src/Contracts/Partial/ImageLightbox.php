<?php declare(strict_types=1);

namespace tiFy\Contracts\Partial;

interface ImageLightbox extends PartialDriver
{
    /**
     * Récupération de l'instance d'un élément.
     *
     * @param ImageLightboxItem|array|string|int $item
     *
     * @return ImageLightboxItem|null
     */
    public function fetchItem($item): ?ImageLightboxItem;

    /**
     * Traitement de la liste des éléments à afficher.
     *
     * @return static
     */
    public function parseItems(): ImageLightbox;
}