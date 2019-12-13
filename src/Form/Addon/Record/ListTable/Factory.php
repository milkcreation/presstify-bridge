<?php declare(strict_types=1);

namespace tiFy\Form\Addon\Record\ListTable;

use tiFy\Form\Concerns\AddonAwareTrait;
use tiFy\Template\Templates\ListTable\Factory as BaseFactory;

class Factory extends BaseFactory
{
    use AddonAwareTrait;
}