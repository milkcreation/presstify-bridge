<?php declare(strict_types=1);

namespace tiFy\Template\Templates\ListTable\Contracts;

use tiFy\Contracts\{
    Support\Collection,
    Template\FactoryAwareTrait
};

interface Extras extends Collection, FactoryAwareTrait
{
    /**
     * Résolution de sortie de la classe sous forme de chaîne de caractère.
     * {@internal Affiche la liste des extras pour le contexte actuel.}
     *
     * @return string
     */
    public function __toString(): string;

    /**
     * Rendu d'affichage de la liste des extras.
     *
     * @return string
     */
    public function render(): string;

    /**
     * Définition du contexte d'affichage de la liste des extras.
     *
     * @param string $which top|bottom
     *
     * @return static
     */
    public function setWhich(string $which): Extras;
}