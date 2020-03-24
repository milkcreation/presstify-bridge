<?php declare(strict_types=1);

namespace tiFy\Template\Templates\ListTable\Contracts;

use Exception;
use tiFy\Contracts\Template\FactoryActions;

interface Actions extends FactoryActions
{
    /**
     * Activation d'éléments.
     * @todo
     *
     * @throws Exception
     */
    public function executeActivate();

    /**
     * Désactivation d'éléments.
     * @todo
     *
     * @throws Exception
     */
    public function executeDeactivate();

    /**
     * Suppression d'éléments.
     * @todo
     *
     * @throws Exception
     */
    public function executeDelete();

    /**
     * Duplication d'éléments.
     * @todo
     *
     * @throws Exception
     */
    public function executeDuplicate();

    /**
     * Mise à la corbeille d'éléments.
     * @todo
     *
     * @throws Exception
     */
    public function executeTrash();

    /**
     * Restauration d'éléments.
     * @todo
     *
     * @throws Exception
     */
    public function executeUntrash();
}