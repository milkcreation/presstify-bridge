<?php

namespace tiFy\Contracts\Kernel;

/**
 * Interface QueryCollection
 * @package tiFy\Contracts\Kernel
 *
 * @deprecated Utiliser tiFy\Contracts\Support\Collection en remplacement
 */
interface QueryCollection extends Collection
{
    /**
     * Récupération du nombre total d'éléments trouvés.
     *
     * @return int
     */
    public function getFounds();

    /**
     * Traitement de la requête de récupération des éléments.
     *
     * @param mixed $args Liste des arguments de requête|Objet, de récupération d'éléments.
     *
     * @return void
     */
    public function query($args);

    /**
     * Définition du nombre total d'éléments trouvés.
     *
     * @return $this
     */
    public function setFounds($founds);
}