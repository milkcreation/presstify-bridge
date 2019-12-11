<?php declare(strict_types=1);

namespace tiFy\Wordpress\Partial;

use tiFy\Contracts\Partial\PartialFactory as BasePartialFactoryContract;
use tiFy\Partial\PartialFactory as BasePartialFactory;

abstract class PartialFactory extends BasePartialFactory
{
    /**
     * {@inheritDoc}
     *
     * @return $this
     */
    public function parse(): BasePartialFactoryContract
    {
        $this->set('viewer.directory', __DIR__ . '/Resources/views/' . $this->getAlias());

        parent::parse();

        $this->parseDefaults();

        return $this;
    }
}