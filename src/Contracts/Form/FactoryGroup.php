<?php declare(strict_types=1);

namespace tiFy\Contracts\Form;

use Illuminate\Support\Collection as laraCollection;
use tiFy\Contracts\Support\ParamsBag;

interface FactoryGroup extends FactoryResolver, ParamsBag
{
    /**
     * Post-affichage.
     *
     * @return string
     */
    public function after(): string;

    /**
     * Pré-affichage.
     *
     * @return string
     */
    public function before(): string;

    /**
     * Récupération de la liste des attributs de balise HTML.
     *
     * @param string $linearized Linératisation des valeurs.
     *
     * @return string|array
     */
    public function getAttrs($linearized = true);

    /**
     * Récupération du nom de qualification du groupe.
     *
     * @return string
     */
    public function getName(): string;

    /**
     * Récupération de la liste des groupes enfants.
     *
     * @return FactoryGroup[]
     */
    public function getChilds(): iterable;

    /**
     * Récupération de la liste des champs associé au groupe.
     *
     * @return laraCollection|FactoryField[]
     */
    public function getFields(): iterable;

    /**
     * Récupération du groupe parent
     *
     * @return FactoryGroup|null
     */
    public function getParent(): ?FactoryGroup;

    /**
     * Récupération du positionnement de l'élément.
     *
     * @return int
     */
    public function getPosition(): int;

    /**
     * Préparation du groupe.
     *
     * @return static
     */
    public function prepare(FactoryGroups $manager): FactoryGroup;
}