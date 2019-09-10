<?php declare(strict_types=1);

namespace tiFy\Contracts\Partial;

interface Modal extends PartialFactory
{
    /**
     * Affichage d'un lien de déclenchement de la modale.
     *
     * @param array $attrs Liste des attributs de configuration.
     *
     * @return string
     */
    public function trigger($attrs = []);

    /**
     * Chargement du contenu de la modale via une requête XHR.
     *
     * @return void
     */
    public function xhrGetContent();
}