<?php declare(strict_types=1);

namespace tiFy\Template\Templates\ListTable;

use Exception;
use tiFy\Template\Factory\Actions as BaseActions;
use tiFy\Template\Templates\ListTable\Contracts\Actions as ActionsContract;

class Actions extends BaseActions implements ActionsContract
{
    /**
     * Instance du gabarit associé.
     * @var Factory
     */
    protected $factory;

    /**
     * @inheritDoc
     * @todo
     */
    public function executeActivate()
    {
        if ($item = $this->factory->builder()->getItem($this->factory->request()->input('id'))) {
            return [
                'success' => true,
                'data'   => $item->getKeyValue()
            ];
        } else {
            throw new Exception(__('Impossible de récupérer l\'élément associé.', 'tify'));
        }
    }

    /**
     * @inheritDoc
     * @todo
     */
    public function executeDeactivate()
    {
        if ($item = $this->factory->builder()->getItem($this->factory->request()->input('id'))) {
            return [
                'success' => true,
                'data'   => $item->getKeyValue()
            ];
        } else {
            throw new Exception(__('Impossible de récupérer l\'élément associé.', 'tify'));
        }
    }

    /**
     * @inheritDoc
     * @todo
     */
    public function executeDelete()
    {
        if ($item = $this->factory->builder()->getItem($this->factory->request()->input('id'))) {
            return [
                'success' => true,
                'data'   => $item->getKeyValue()
            ];
        } else {
            throw new Exception(__('Impossible de récupérer l\'élément associé.', 'tify'));
        }
    }

    /**
     * @inheritDoc
     * @todo
     */
    public function executeDuplicate()
    {
        if ($item = $this->factory->builder()->getItem($this->factory->request()->input('id'))) {
            return [
                'success' => true,
                'data'   => $item->getKeyValue()
            ];
        } else {
            throw new Exception(__('Impossible de récupérer l\'élément associé.', 'tify'));
        }
    }

    /**
     * @inheritDoc
     * @todo
     */
    public function executeTrash()
    {
        if ($item = $this->factory->builder()->getItem($this->factory->request()->input('id'))) {
            return [
                'success' => true,
                'data'   => $item->getKeyValue()
            ];
        } else {
            throw new Exception(__('Impossible de récupérer l\'élément associé.', 'tify'));
        }
    }

    /**
     * @inheritDoc
     * @todo
     */
    public function executeUntrash()
    {
        if ($item = $this->factory->builder()->getItem($this->factory->request()->input('id'))) {
            return [
                'success' => true,
                'data'   => $item->getKeyValue()
            ];
        } else {
            throw new Exception(__('Impossible de récupérer l\'élément associé.', 'tify'));
        }
    }
}