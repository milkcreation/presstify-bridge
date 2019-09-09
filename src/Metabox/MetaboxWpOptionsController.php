<?php declare(strict_types=1);

namespace tiFy\Metabox;

use tiFy\Contracts\Metabox\{MetaboxController as MetaboxControllerContract,
    MetaboxWpOptionsController as MetaboxWpOptionsControllerContract};

abstract class MetaboxWpOptionsController extends MetaboxController implements MetaboxWpOptionsControllerContract
{
    /**
     * @inheritDoc
     */
    public function content($args = null, $null1 = null, $null2 = null)
    {
        return parent::content($args, $null1, $null2);
    }

    /**
     * @inheritDoc
     */
    public function getOptionsPage()
    {
        return $this->getObjectName();
    }

    /**
     * @inheritDoc
     */
    public function header($args = null, $null1 = null, $null2 = null)
    {
        return parent::header($args, $null1, $null2);
    }

    /**
     * @inheritDoc
     */
    public function prepare(): MetaboxControllerContract
    {
        parent::prepare();

        foreach ($this->settings() as $setting => $attrs) {
            if (is_numeric($setting)) {
                $setting = (string)$attrs;
                $attrs = [];
            }

            register_setting($this->getOptionsPage(), $setting, $attrs);
        }

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function settings()
    {
        return [];
    }
}