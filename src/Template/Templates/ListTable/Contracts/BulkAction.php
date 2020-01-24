<?php declare(strict_types=1);

namespace tiFy\Template\Templates\ListTable\Contracts;

use tiFy\Contracts\Field\SelectChoice;
use tiFy\Contracts\Template\FactoryAwareTrait;

interface BulkAction extends FactoryAwareTrait, SelectChoice
{

}