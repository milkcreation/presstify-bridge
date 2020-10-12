<?php declare(strict_types=1);

namespace tiFy\Template\Templates\FileManager;

use tiFy\Template\Factory\{Ajax as BaseAjax, FactoryAwareTrait};
use tiFy\Template\Templates\FileManager\Contracts\{Ajax as AjaxContract, FileManager};

class Ajax extends BaseAjax implements AjaxContract
{
    use FactoryAwareTrait;

    /**
     * Instance du gabarit associé.
     * @var FileManager
     */
    protected $factory;

    /**
     * @inheritDoc
     */
    public function parse()
    {
        parent::parse();

        $this->getFactory()->param()->set('attrs.data-options.ajax', $this->all());

        return $this;
    }
}