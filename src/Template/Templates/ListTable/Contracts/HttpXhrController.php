<?php declare(strict_types=1);

namespace tiFy\Template\Templates\ListTable\Contracts;

use tiFy\Contracts\Template\FactoryHttpXhrController;

interface HttpXhrController extends FactoryHttpXhrController
{
    /**
     * @inheritDoc
     */
    public function post();
}