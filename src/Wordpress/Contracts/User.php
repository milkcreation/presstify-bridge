<?php

namespace tiFy\Wordpress\Contracts;

interface User
{
    /**
     * Récupération d'une liste d'utilisateur sous la forme d'un tableau indexé au format clé => valeur.
     * @internal Tous les arguments de requête sont disponibles à l'exception de fields qui est court-circuité par la méthode.
     *
     * @param string $value Champ utilisé comme valeur du tableau de sortie.
     * @param string $key Champ utilisé comme clé du tableau de sortie.
     * @param array $query_args Liste des arguments de requête (hors fields).
     *
     * @return array
     */
    public function pluck($value = 'display_name', $key = 'ID', $query_args = []);

    /**
     * Récupération du nom d'affichage d'un rôle.
     *
     * @param string $role Nom de qualification du rôle.
     *
     * @return string
     */
    public function roleDisplayName($role);
}