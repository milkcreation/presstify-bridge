<?php

namespace tiFy\Contracts\Db;

interface DbFactoryParser extends DbFactoryResolverTrait
{
    /**
     * Traitement de la requête SQL
     *
     * @param array $query_vars Liste des arguments de requête
     *
     * @return null|string
     */
    public function query($query_vars = []);

    /**
     * Traitements des arguments de requête
     */
    public function query_vars($vars, $defaults = null);

    /**
     * Récupération de la condition SELECT
     *
     * @param string[] $fields
     *
     * @return null|string
     */
    public function clause_select($fields = null);

    /**
     * Récupération de la condition FROM
     *
     * @return string
     */
    public function clause_from();

    /**
     * Traitement de la condition JOIN
     *
     * @return null|string
     */
    public function clause_join();

    /**
     * Traitement de la condition WHERE
     *
     * @param string $vars
     *
     * @return string
     */
    public function clause_where($vars);

    /**
     * Traitement des comparaison de valeur la condition WHERE
     *
     * @param string $col_value
     *
     * @return string
     */
    public function clause_where_compare_value($col_value);

    /**
     * Traitement de la condition WHERE dans le cadre d'une recherche
     *
     * @param string $terms
     *
     * @return null|string
     */
    public function clause_search($terms = '');

    /**
     * Traitement de la condition WHERE dans le cadre d'une inclusion d'éléments
     *
     * @param int[] $ids
     *
     * @return null|string
     */
    public function clause__in($ids);

    /**
     * Traitement de la condition WHERE dans le cadre d'une exclusion d'éléments
     *
     * @param int[] $ids
     *
     * @return null|string
     */
    public function clause__not_in($ids);

    /**
     * Traitement de la condition GROUPBY
     *
     * @return null|string
     */
    public function clause_group_by();

    /**
     * Traitement de la condition ORDER
     */
    public function clause_order($orderby, $order = 'DESC');

    /**
     * Traitement du champs d'ordonnacement
     *
     * @param string $orderby
     *
     * @return string
     */
    public function parse_orderby($orderby);

    /**
     * Traitement du facteur d'ordonnacement
     *
     * @param string $order ASC|DESC
     *
     * @return string
     */
    public function parse_order($order = null);

    /**
     * Traitement de la condition LIMIT
     *
     * @param int $per_page Nombre d'éléments par page de résultat
     * @param int $paged Page courante
     *
     * @return null|string
     */
    public function clause_limit($per_page = 0, $paged = 1);

    /**
     * Traitement des resultats de requête
     *
     * @param object $results Resultats de requête brut
     * @param string $output Format de sortie
     *
     * @return mixed
     */
    public function parse_output($results, $output = OBJECT);

    /**
     * Vérification des arguments de requête
     *
     * @param array $query_vars Variables de requête
     *
     * @return array
     */
    public function validate($query_vars);
}