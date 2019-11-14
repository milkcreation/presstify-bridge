<?php declare(strict_types=1);

namespace tiFy\Template\Templates\FileManager;

use tiFy\Template\Factory\FactoryAwareTrait;
use tiFy\Template\Templates\FileManager\Contracts\{Ajax as AjaxContract, FileManager};
use tiFy\Support\ParamsBag;

class Ajax extends ParamsBag implements AjaxContract
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
    public function defaults()
    {
        return [
            'url'      => $this->getFactory()->baseUrl() . '/xhr',
            'dataType' => 'json',
            'type'     => 'POST'
        ];
    }

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