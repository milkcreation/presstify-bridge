<?php declare(strict_types=1);

namespace tiFy\Contracts\Partial;

use tiFy\Contracts\Support\ParamsBag;

interface ImageLightboxItem extends ParamsBag
{
    /**
     * Récupération des attributs HTML du lien.
     *
     * @param boolean $linearize
     *
     * @return array|string
     */
    public function getAttrs(bool $linearize = true);

    /**
     * Affichage de la miniature.
     *
     * @return string
     */
    public function getContent(): string;
}