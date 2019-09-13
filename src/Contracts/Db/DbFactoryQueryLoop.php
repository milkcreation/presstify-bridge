<?php

namespace tiFy\Contracts\Db;

interface DbFactoryQueryLoop extends DbFactoryResolverTrait
{
    /**
     * @todo
     */
    public function getAdjacent($previous = true, $args = []);

    /**
     * Récupération du nombre d'éléments récupérés.
     *
     * @return array
     */
    public function getCount();

    /**
     * Récupération de l'attribut de l'élément dans la boucle.
     *
     * @param string $key Clé d'indexe de l'attribut.
     * @param mixed $default Valeur de retour par défaut.
     *
     * @return mixed
     */
    public function getField($key, $default = '');

    /**
     * Récupération du nombre total d'éléments trouvés, correspondant aux variables de requête passé en argument.
     *
     * @return array
     */
    public function getFoundItems();

    /**
     * Récupération de la liste des éléments récupérés.
     *
     * @return array
     */
    public function getItems();

    /**
     * Récupération d'une metadonnée de l'élément dans la boucle.
     *
     * @param string $meta_key Clé d'indexe de la metadonnée.
     * @param mixed $default Valeur de retour par défaut.
     * @param bool $single Indicateur de métadonnée simple ou multiple.
     *
     * @return mixed|void
     */
    public function getMeta($meta_key, $default = '', $single = true);

    /**
     * Vérifie l'existance d'éléments complémentaire dans la boucle.
     *
     * @return bool
     */
    public function haveItems();

    /**
     * Définition du prochain élément dans boucle.
     *
     * @return object
     */
    public function nextItem();

    /**
     * Traitement des variable de requête et récupération des éléments en base.
     *
     * @param array $query Liste des arguments de requêtes personnalisés.
     *
     * @return array
     */
    public function query($query = []);

    /**
     * Récupération de la liste des éléments basé sur la configuration.
     *
     * @return array
     */
    public function queryItems();

    /**
     * Réinitialisation des propriétés et définition des valeurs par defaut.
     *
     * @return void
     */
    public function reset();

    /**
     * Réinitialisation de la boucle.
     *
     * @return void
     */
    public function rewindItems();

    /**
     * Définition de l'élément courant dans la boucle.
     *
     * @return void
     */
    public function theItem();
}