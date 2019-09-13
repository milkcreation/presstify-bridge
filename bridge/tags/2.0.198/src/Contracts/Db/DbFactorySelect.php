<?php

namespace tiFy\Contracts\Db;

interface DbFactorySelect extends DbFactoryResolverTrait
{
    /**
     * Récupération de la liste des résultats.
     *
     * @return mixed
     */
    public function getResults();

    /**
     * Compte le nombre d'éléments correspondants aux critère de requête.
     *
     * @param array $query_args Critère de requêtede récupération de données en base.
     *
     * @return int
     */
    public function count($query_args = []);

    /**
     * Vérification de l'existance de la valeur d'une données correspondants aux critère de requête.
     *
     * @param array $query_args Critère de requêtede récupération de données en base.
     *
     * @return int
     */
    public function has($col_name = null, $value = '', $query_args = []);

    /** == Récupération de l'id d'un élément selon des critères == **/
    public function id($query_args = []);

    /** == Récupération de la valeur d'un cellule selon des critères == **/
    public function cell($col_name = null, $query_args = []);

    /** == Récupération de la valeur d'un cellule selon son l'id de l'élément == **/
    public function cell_by_id($id, $col_name);

    /** == Récupération des valeurs d'une colonne de plusieurs éléments selon des critères == **/
    public function col($col_name = null, $query_args = []);

    /** == Récupération des valeurs de la colonne id de plusieurs éléments selon des critères == **/
    public function col_ids($query_args = []);
    /**
     * Retourne un tableau indexé sous la forme couple clé <> valeur
     *
     * @param string $value_col Colonne utilisée en tant que valeur du couple
     * @param string $key_col Colonne utilisée en tant que clé du couple
     * @param array $query_args Liste des arguments de requête
     * @param string $output Format de sortie
     *
     * @return null|array
     */
    public function pairs($value_col, $key_col = '', $query_args = []);

    /* = LIGNE = */
    /** == Récupération des arguments d'un élément selon des critères == **/
    public function row($query_args = [], $output = OBJECT);

    /** == Récupération d'un élément selon un champ et sa valeur == **/
    public function row_by($col_name = null, $value, $output = OBJECT);

    /** == Récupération des arguments d'un élément selon son id == **/
    public function row_by_id($id, $output = OBJECT);

    /* = LIGNES = */
    /** == Récupération des arguments de plusieurs éléments selon des critères == **/
    public function rows($query_args = [], $output = OBJECT);

    /**
     *
     */
    /** == Récupération de l'élément voisin selon un critère == **/
    public function adjacent($id, $previous = true, $query_args = [], $output = OBJECT);

    /* == Récupération de l'élément précédent == */
    public function previous($id, $query_args = [], $output = OBJECT);

    /* == Récupération de l'élément suivant == */
    public function next($id, $query_args = [], $output = OBJECT);
}