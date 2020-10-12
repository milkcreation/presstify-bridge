<?php

namespace tiFy\Contracts\Form;

interface FactoryValidation
{
    /**
     * Appel d'un test d'intégrité de valeur.
     *
     * @param string|callable $cb Fonction de traitement de vérification.
     * @param mixed $value Valeur à vérifier.
     * @param array $args Liste des variables passées en argument.
     *
     * @return boolean
     */
    public function call($callback, $value, $args = []);

    /**
     * Méthode de controle par défaut.
     *
     * @param mixed $value Valeur à vérifier.
     *
     * @return boolean
     */
    public function __return_true($value);

    /**
     * Compare deux chaînes de caractères.
     * @internal ex. mot de passe <> confirmation mot de passe
     *
     * @param mixed $value Valeur du champ courant à comparer.
     * @param mixed $tags Variables de qualification de champs de comparaison.
     * @param boolean $raw Récupération du format brut du champ de comparaison.
     *
     * @return boolean
     */
    public function compare($value, $tags, $raw = true);
}