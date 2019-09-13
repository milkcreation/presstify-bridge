<?php

namespace tiFy\Wordpress\PageHook\Admin;

use tiFy\Contracts\Metabox\MetaboxController as MetaboxControllerContract;
use tiFy\Wordpress\Contracts\PageHookItem;
use tiFy\Metabox\MetaboxWpOptionsController;

class PageHookAdminOptions extends MetaboxWpOptionsController
{
    /**
     * @inheritDoc
     */
    public function content($var1 = null, $var2 = null, $var3 = null)
    {
        return $this->viewer('content', $this->all());
    }

    /**
     * @inheritDoc
     */
    public function header($var1 = null, $var2 = null, $var3 = null)
    {
        return $this->item->getTitle() ? : __('Page d\'accroche', 'tify');
    }

    /**
     * @inheritDoc
     */
    public function parse(): MetaboxControllerContract
    {
        parent::parse();

        $this->set('items', page_hook()->all());

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function settings()
    {
        $settings = [];
        foreach($this->get('items', []) as $item) {
            /** @var PageHookItem $item */
            array_push($settings, $item->getOptionName());
        }

        return $settings;
    }
}