<?php

namespace tiFy\Contracts\Column;

interface ColumnDisplayUserInterface extends ColumnDisplayInterface
{
    /**
     * Affichage du contenu de la colonne.
     *
     * @param string $content Contenu de la colonne.
     * @param string $column_name Identification de la colonne.
     * @param int $user_id Identifiant de qualification de l'utilisateur.
     *
     * @return void
     */
    public function content($content = null, $column_name = null, $user_id = null);
}