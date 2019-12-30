<?php declare(strict_types=1);

namespace tiFy\Template\Templates\ListTable;

use tiFy\Field\Driver\Select\SelectChoice;
use tiFy\Template\Factory\FactoryAwareTrait;
use tiFy\Template\Templates\ListTable\Contracts\BulkAction as BulkActionContract;

class BulkAction extends SelectChoice implements BulkActionContract
{
    use FactoryAwareTrait;

    /**
     * Instance du gabarit associé.
     * @var Factory
     */
    protected $factory;
}