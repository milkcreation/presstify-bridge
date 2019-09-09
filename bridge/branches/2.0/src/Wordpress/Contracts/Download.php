<?php

namespace tiFy\Wordpress\Contracts;

interface Download
{
    /**
     * Déclaration de permission de téléchargement d'un fichier.
     *
     * @param string|int $file Chemin relatif|Url|Identifiant d'un fichier de la médiathèque.
     *
     * @return void
     */
    public function register($file);

    /**
     * Url de téléchargement d'un fichier.
     *
     * @param string|int $file Chemin relatif|Chemin absolu|Url|Identifiant d'un fichier de la médiathèque
     * @param array $query_vars Arguments de requête complémentaires
     *
     * @return string
     */
    public function url($file, $query_vars = []);
}