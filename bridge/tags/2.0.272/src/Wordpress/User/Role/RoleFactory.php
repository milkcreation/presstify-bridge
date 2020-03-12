<?php

namespace tiFy\Wordpress\User\Role;

use tiFy\User\Role\RoleFactory as BaseRoleFactory;

class RoleFactory extends BaseRoleFactory
{
    /**
     * @inheritdoc
     */
    public function boot(): void
    {
        parent::boot();

        /*if (!$this->get('admin_bar') && current_user_can($this->getName()) && !is_admin()) {
            show_admin_bar(false);
        }*/
    }

    /**
     * @inheritdoc
     */
    public function defaults(): array
    {
        return array_merge(parent::defaults(), [
            'admin_bar' => in_array($this->getName(), ['administrator']) ? true : false
        ]);
    }
}