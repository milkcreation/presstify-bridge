<?php declare(strict_types=1);

namespace tiFy\Contracts\Form;

use Illuminate\Support\Collection as laraCollection;
use tiFy\Contracts\Support\Collection;

interface FactoryFields extends FactoryResolver, Collection
{
    /**
     * Récupération de la liste des champs par groupe d'appartenance.
     *
     * @param string $name Nom de qualification du groupe
     *
     * @return laraCollection|FactoryField[]|null
     */
    public function fromGroup(string $name): ?iterable;
}