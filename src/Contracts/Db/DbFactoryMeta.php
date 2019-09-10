<?php

namespace tiFy\Contracts\Db;

interface DbFactoryMeta extends DbFactoryResolverTrait
{
    /**
     * Ajout d'une metadonnée pour un élément.
     *
     * @param int $id Identifiant de qualification de l'élément dans la table principale.
     * @param string $meta_key Index de la métadonnée.
     * @param mixed $meta_value Valeur de la métadonnée. Les données non scalaires seront serialisées.
     * @param bool $unique Optionnel, true par défaut. Permet de définir si seule une metadonnée avec la même clé d'index (meta_key) est autorisée.
     *
     * @return int
     */
    public function add($object_id, $meta_key, $meta_value, $unique = true);

    /**
     * Récupération de toutes les metadonnés d'un élément.
     *
     * @param int $id Identifiant de la clé primaire de l'élément dans la table principale.
     *
     * @return array
     */
    public function all($object_id);

    /**
     * Suppression de métadonnée associée à un élément.
     *
     * @param int $id Identifiant de qualification de l'élément dans la table principale.
     * @param string $meta_key Index de la métadonnée.
     * @param mixed $meta_value Valeur de la métadonnée à supprimer.
     * @param bool $delete_all Permet la suppression de toutes le metadonnées pour une même meta_key. $objet_id doit valoir 0.
     *
     * @return bool
     */
    public function delete($object_id, $meta_key, $meta_value = '', $delete_all = false);

    /**
     * Suppression de toutes les métadonnées d'un élément.
     *
     * @return void
     */
    public function deleteAll($id);

    /**
     * Récupération de la valeur de la metadonnée d'un élément
     *
     * @param int $id ID de l'item
     * @param string $meta_key Optionel. Index de la métadonnée. Retournera, s'il n'est pas spécifié toutes les metadonnées relative à l'objet.
     * @param bool $single Optionel, default is true. Si true, retourne uniquement la première valeur pour l'index meta_key spécifié. Ce paramètre n'a pas d'impact lorsqu'aucun index meta_key n'est spécifié.
     *
     * @return mixed
     */
    public function get($object_id, $meta_key = '', $single = true);

    /**
     * Récupération de la valeur de la metadonnée d'un selon son identifiant de clé primaire de table des metadonnés.
     *
     * @param int $meta_id Identifiant de clé primaire de l'éléments dans la table des métadonnées.
     *
     * @return mixed
     */
    public function getByMid($meta_id);

    /**
     * Nom de la colonne de jointure
     *
     * @return string
     */
    public function getJoinCol();

    /**
     * Nom de la colonne de clé primaire.
     *
     * @var string
     */
    public function getPrimary();

    /**
     * Récupération du nom préfixé (réel) de la table d'enregistrement des métadonnées.
     *
     * @var string
     */
    public function getTableName();
    /**
     * Mise à jour d'une metadonnée pour un élément.
     *
     * @param int $id Identifiant de qualification de l'élément dans la table principale.
     * @param string $meta_key Index de la métadonnée.
     * @param mixed $meta_value Valeur de la métadonnée. Les données non scalaires seront serialisées.
     * @param mixed $prev_value Optionnel. Valeur de contrôle de la métadonnée.
     *
     * @return bool
     */
    public function update($object_id, $meta_key, $meta_value, $prev_value = '');
}