<?php declare(strict_types=1);

namespace tiFy\Template\Templates\FileManager;

use tiFy\Template\Factory\Params as BaseParams;
use tiFy\Template\Templates\FileManager\Contracts\Params as ParamsContract;

class Params extends BaseParams implements ParamsContract
{
    /**
     * Instance du gabarit associé.
     * @var FileManager
     */
    protected $factory;

    /**
     * @inheritDoc
     */
    public function defaults(): array
    {
        return array_merge(parent::defaults(), []);
    }

    /**
     * @inheritDoc
     */
    public function parse(): self
    {
        parent::parse();

        $this->set('attrs.data-control', 'file-manager');

        return $this;
    }
}