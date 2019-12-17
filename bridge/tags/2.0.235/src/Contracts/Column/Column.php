<?php

namespace tiFy\Contracts\Column;

use Illuminate\Support\Collection;

interface Column
{
    /**
     * Ajout d'un élément.
     *
     * @param string $screen Ecran d'affichage de l'élément.
     * @param string $name Nom de qualification.
     * @param array $attrs Liste des attributs de configuration de l'élément.
     *
     * @return $this
     */
    public function add($screen, $name, $attrs = []);

    /**
     * Traitement de la liste des entêtes de colonnes.
     *
     * @param array $headers Liste des entêtes de colonnes.
     *
     * @return array
     */
    public function parseColumnHeaders($headers);

    /**
     * Traitement de la liste des contenus de colonnes.
     *
     * @return string
     */
    public function parseColumnContents();
}