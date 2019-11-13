<?php declare(strict_types=1);

namespace tiFy\Wordpress\Partial\Partials\Sidebar;

use tiFy\Partial\Partials\Sidebar\Sidebar as BaseSidebar;
use tiFy\Wordpress\Contracts\Partial\PartialFactory as PartialFactoryContract;

class Sidebar extends BaseSidebar implements PartialFactoryContract
{
    /**
     * @inheritDoc
     */
    public function boot(): void
    {
        add_action('init', function () {
            wp_register_style(
                'PartialSidebar',
                asset()->url('partial/sidebar/css/styles.css'),
                [],
                180511
            );
            wp_register_script(
                'PartialSidebar',
                asset()->url('partial/sidebar/css/scripts.js'),
                ['jquery'],
                180511,
                true
            );
        });
    }

    /**
     * @inheritDoc
     */
    public function enqueue(): PartialFactoryContract
    {
        wp_enqueue_style('PartialSidebar');
        wp_enqueue_script('PartialSidebar');

        return $this;
    }
}