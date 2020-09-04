<?php declare(strict_types=1);

namespace tiFy\Wordpress\Metabox\Context;

use tiFy\Metabox\MetaboxContext;

class SideContext extends MetaboxContext
{
    /**
     * @inheritDoc
     */
    public function defaults(): array
    {
        return array_merge(parent::defaults(), [

        ]);
    }

    /**
     * @inheritDoc
     */
    public function parse()
    {
        parent::parse();

        return $this;
    }
}