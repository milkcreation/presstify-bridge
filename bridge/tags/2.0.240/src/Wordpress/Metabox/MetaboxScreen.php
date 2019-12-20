<?php declare(strict_types=1);

namespace tiFy\Wordpress\Metabox;

use tiFy\Metabox\MetaboxScreen as BaseMetaboxScreen;
use tiFy\Wordpress\Routing\WpScreen;

class MetaboxScreen extends BaseMetaboxScreen
{
    /**
     * @inheritDoc
     */
    public function isCurrent(): bool
    {
        if (is_null($this->current)) {
            if ($this->isCurrentRoute()) {
                $this->current = true;
            } elseif ($this->isCurrentRequest()) {
                $this->current = true;
            } elseif ($this->isCurrentWpScreen()) {
                $this->current = true;
            } else {
                $this->current = false;
            }
        }

        return $this->current;
    }

    /**
     * @inheritDoc
     */
    public function isCurrentWpScreen(): bool
    {
        $screen = (preg_match('/(.*)@(post_type|taxonomy|user)/', $this->name)) ? "edit::{$this->name}" : $this->name;

        return (WpScreen::get($screen))->isCurrent();
    }
}