<?php declare(strict_types=1);

namespace tiFy\Template\Factory;

use tiFy\Contracts\Template\FactoryAjax as AjaxContract;
use tiFy\Support\ParamsBag;

class Ajax extends ParamsBag implements AjaxContract
{
    use FactoryAwareTrait;

    /**
     * @inheritDoc
     */
    public function defaults()
    {
        return [
            'ajax'        => [
                'url'      => $this->factory->url()->xhr(),
                'dataType' => 'json',
                'method'   => 'POST',
            ],
            'data'        => []
        ];
    }
}