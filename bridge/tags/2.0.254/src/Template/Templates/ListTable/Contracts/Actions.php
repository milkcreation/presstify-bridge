<?php declare(strict_types=1);

namespace tiFy\Template\Templates\ListTable\Contracts;

use tiFy\Contracts\Template\FactoryActions;

interface Actions extends FactoryActions
{
    /**
     * Activation d'éléments.
     * @todo
     */
    public function executeActivate();

    /**
     * Désactivation d'éléments.
     * @todo
     */
    public function executeDeactivate();

    /**
     * Suppression d'éléments.
     * @todo
     */
    public function executeDelete();

    /**
     * Duplication d'éléments.
     * @todo
     */
    public function executeDuplicate();

    /**
     * Mise à la corbeille d'éléments.
     * @todo
     */
    public function executeTrash();

    /**
     * Restauration d'éléments.
     * @todo
     */
    public function executeUntrash();
}