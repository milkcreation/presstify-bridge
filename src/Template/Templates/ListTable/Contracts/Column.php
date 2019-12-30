<?php declare(strict_types=1);

namespace tiFy\Template\Templates\ListTable\Contracts;

use tiFy\Contracts\Support\ParamsBag;
use tiFy\Contracts\Template\FactoryAwareTrait;

interface Column extends FactoryAwareTrait, ParamsBag
{
    /**
     * Résolution de sortie de la classe en tant que chaîne de caractère.
     *
     * @return string
     */
    public function __toString(): string;

    /**
     * Indicateur d'utilisation d'un colonne en tant que colonne principale.
     *
     * @return boolean
     */
    public function canUseForPrimary(): bool;

    /**
     * Récupération du nom de qualification.
     *
     * @return string
     */
    public function getName(): string;

    /**
     * Récupération de l'intitulé de qualification.
     *
     * @return string
     */
    public function getTitle(): string;

    /**
     * Récupération du gabarit d'affichage du contenu de la colonne.
     *
     * @param string $default Valeur de retour par défaut.
     *
     * @return string
     */
    public function getTemplate(string $default = 'tbody-col'): string;

    /**
     * Récupération de l'entête au format HTML.
     *
     * @param boolean $with_id Activation de l'id de la balise HTML.
     *
     * @return string
     */
    public function header(bool $with_id = true): string;

    /**
     * Vérification de maquage de la colonne.
     *
     * @return boolean
     */
    public function isHidden(): bool;

    /**
     * Vérifie si la colonne est la colonne principale.
     *
     * @return boolean
     */
    public function isPrimary(): bool;

    /**
     * Vérifie si la colonne peut être ordonnancée.
     *
     * @return boolean
     */
    public function isSortable(): bool;

    /**
     * Vérification d'affichage de la colonne.
     *
     * @return boolean
     */
    public function isVisible(): bool;

    /**
     * {@inheritDoc}
     *
     * @return static
     */
    public function parse(): Column;

    /**
     * Affichage
     *
     * @return string
     */
    public function render(): string;

    /**
     * Définition du nom de qualification.
     *
     * @param string $name Nom de qualification.
     *
     * @return static
     */
    public function setName(string $name): Column;

    /**
     * Récupération de la valeur à afficher.
     *
     * @return string
     */
    public function value(): string;
}