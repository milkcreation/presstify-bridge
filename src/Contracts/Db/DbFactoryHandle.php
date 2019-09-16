<?php

namespace tiFy\Contracts\Db;

interface DbFactoryHandle extends DbFactoryResolverTrait
{
    /**
     * Création d'un nouvel élément en base de données.
     *
     * @param array $data Liste des données de l'élément.
     *
     * @return int
     */
    public function create($data = []);

    /**
     * @todo
     */
    public function delete($where, $where_format = null);

    /**
     * Suppression d'un élément en base de données.
     *
     * @param mixed $id Identifiant de qualification de la clé primaire.
     *
     * @return int
     */
    public function delete_by_id($id);

    /**
     * Récupération de la valeur numérique de la prochaine clé primaire
     *
     * @return int
     */
    public function next();

    /**
     * @todo
     */
    public function prepare($query, $args);

    /**
     * @todo
     */
    public function query($query);

    /**
     * Enregistrement d'un élément en base de données.
     *
     * @param array $data Liste des données de l'élément.
     *
     * @return int
     */
    public function record($data = []);

    /**
     * @todo
     */
    public function replace($data = [], $format = null);

    /**
     * Mise à jour d'un élément en base de données.
     *
     * @param mixed $id Identifiant de qualification de la clé primaire.
     * @param array $data Liste des données de l'élément.
     *
     * @return int
     */
    public function update($id, $data = []);
}