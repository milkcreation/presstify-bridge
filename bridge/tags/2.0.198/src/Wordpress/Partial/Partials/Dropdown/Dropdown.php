<?php declare(strict_types=1);

namespace tiFy\Wordpress\Partial\Partials\Dropdown;

use tiFy\Partial\Partials\Dropdown\Dropdown as BaseDropdown;
use tiFy\Wordpress\Contracts\Partial\PartialFactory as PartialFactoryContract;

class Dropdown extends BaseDropdown implements PartialFactoryContract
{
    /**
     * @inheritDoc
     */
    public function boot(): void
    {
        add_action('init', function () {
            wp_register_style(
                'PartialDropdown',
                asset()->url('partial/dropdown/css/styles.css'),
                [],
                181221
            );
            wp_register_script(
                'PartialDropdown',
                asset()->url('partial/dropdown/js/scripts.js'),
                ['jquery-ui-widget'],
                181221,
                true
            );
        });
    }

    /**
     * @inheritDoc
     */
    public function enqueue(): PartialFactoryContract
    {
        wp_enqueue_style('PartialDropdown');
        wp_enqueue_script('PartialDropdown');

        return $this;
    }
}