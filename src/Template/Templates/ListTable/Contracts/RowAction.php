<?php declare(strict_types=1);

namespace tiFy\Template\Templates\ListTable\Contracts;

use tiFy\Contracts\Support\ParamsBag;
use tiFy\Contracts\Template\FactoryAwareTrait;

interface RowAction extends FactoryAwareTrait, ParamsBag
{
    /**
     * Résolution de sortie de la classe en tant que chaîne de caractère.
     *
     * @return string
     */
    public function __toString(): string;

    /**
     * Récupération de l'url de base du lien.
     *
     * @return string
     */
    public function getBaseUrl(): string;

    /**
     * Récupération du nom de qualification.
     *
     * @return string
     */
    public function getName(): string;

    /**
     * Vérification de disponibilité de l'action.
     *
     * @return boolean
     */
    public function isAvailable(): bool;

    /**
     * Activation du traitement de l'action via ajax.
     *
     * @return boolean
     */
    public function isXhr(): bool;

    /**
     * {@inheritDoc}
     *
     * @return static
     */
    public function parse(): RowAction;

    /**
     * Traitement de l'url du lien.
     *
     * @return static
     */
    public function parseUrl(): RowAction;

    /**
     * Récupération du rendu de l'affichage.
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
    public function setName(string $name): RowAction;
}