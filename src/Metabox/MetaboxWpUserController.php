<?php declare(strict_types=1);

namespace tiFy\Metabox;

use tiFy\Contracts\Metabox\MetaboxWpUserController as MetaboxWpUserControllerContract;

abstract class MetaboxWpUserController extends MetaboxController implements MetaboxWpUserControllerContract
{
    /**
     * @inheritDoc
     */
    public function content($user = null, $args = null, $null = null)
    {
        return parent::content($user, $args, $null);
    }

    /**
     * @inheritDoc
     */
    public function header($user = null, $args = null, $null = null)
    {
        return parent::header($user, $args, $null);
    }
}