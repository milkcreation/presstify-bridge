<?php

namespace tiFy\Contracts\Form;

use tiFy\Contracts\Kernel\Collection;

interface FactoryButtons extends FactoryResolver, Collection
{
    /**
     * Récupération de la liste des éléments par ordre d'affichage.
     *
     * @return ButtonController[]
     */
    public function byPosition();
}