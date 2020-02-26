<?php declare(strict_types=1);

namespace tiFy\Wordpress\Partial;

use tiFy\Contracts\Partial\PartialDriver as BasePartialDriverContract;
use tiFy\Partial\PartialDriver as BasePartialDriver;

abstract class PartialDriver extends BasePartialDriver
{
    /**
     * {@inheritDoc}
     *
     * @return $this
     */
    public function parse(): BasePartialDriverContract
    {
        if (file_exists(__DIR__ . '/Resources/views/' . $this->getAlias())) {
            $this->set('viewer.directory', __DIR__ . '/Resources/views/' . $this->getAlias());
        }

        parent::parse();

        $this->parseDefaults();

        return $this;
    }
}