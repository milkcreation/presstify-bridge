<?php

namespace tiFy\Contracts\Db;

use tiFy\Contracts\Support\ParamsBag;

interface DbFactory extends DbFactoryResolverTrait, ParamsBag
{
    /**
     * Récupération du nom réél (prefixé) d'une colonne.
     *
     * @param string $name Alias de qualification de la colonne ou nom réel (préfixé) de la colonne.
     *
     * @return string Nom de la colonne préfixée
     */
    public function existsCol($name);

    /**
     * Récupération d'un attribut de configuration de colonne.
     *
     * @param string $name Identifiant de qualification de la colonne ou nom réel (préfixé) de la colonne.
     * @param string $key Index de qualification de l'attribut.
     * @param mixed $default Valeur de retour par défaut.
     *
     * @return mixed
     */
    public function getColAttr($name, $key, $default = '');

    /**
     * Récupération des attributs de configuration d'une colonne.
     *
     * @param string $name Identifiant de qualification de la colonne ou nom réel (préfixé) de la colonne.
     *
     * @return array
     */
    public function getColAttrs($name);

    /**
     * Récupération du nom préfixé d'une colonne selon son alias de qualification.
     *
     * @param string $alias Alias de qualification d'une colonne.
     *
     * @return string
     */
    public function getColMap($alias);
    /**
     * Récupération de la liste des noms de colonnes réels (préfixés)
     *
     * @return string[]
     */
    public function getColNames();

    /**
     * Récupération du préfixe des colonnes de la table.
     *
     * @return string
     */
    public function getColPrefix();

    /**
     * Récupération des clés d'index.
     *
     * @return array
     */
    public function getIndexKeys();

    /**
     * Récupération du nom de la colonne de jointure de la table d'enregistrement des metadonnées.
     *
     * @var string
     */
    public function getMetaJoinCol();

    /**
     * Récupération de l'identifiant de qualification la table d'enregistrement des metadonnées.
     *
     * @var string
     */
    public function getMetaType();

    /**
     * Récupération du nom de qualification.
     *
     * @return string
     */
    public function getName();

    /**
     * Récupération de la clé primaire
     *
     * @return string
     */
    public function getPrimary();

    /**
     * Récupération de la liste des colonnes ouvertes à la recherche de termes.
     *
     * @return array
     */
    public function getSearchColumns();

    /**
     * Récupération du nom de la table préfixée
     *
     * @return string
     */
    public function getTableName();

    /**
     * Vérification d'existance de gestion des metadonnée par le controleur.
     *
     * @return boolean
     */
    public function hasMeta();

    /**
     * Vérification d'existance de colonnes ouvertes à la recherche de termes.
     *
     * @return boolean
     */
    public function hasSearch();

    /**
     * Installation des tables associées.
     *
     * @return void
     */
    public function install();

    /**
     * Vérification si une colonne est la colonne déclarée comme primaire.
     *
     * @param string $name Identifiant de qualification de la colonne ou nom réel (préfixé) de la colonne.
     *
     * @return bool
     */
    public function isPrimary($name);

    /**
     * Vérifie si une variable de requête est une variable reservée par le système.
     *
     * @param string $var Variable de requête.
     *
     * @return boolean
     */
    public function isPrivateQueryVar($var);

    /**
     * Moteur de requête SQL
     *
     * @return \wpdb
     */
    public function sql();
}