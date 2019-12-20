<?php declare(strict_types=1);

namespace tiFy\Wordpress\Partial\Partials\Notice;

use tiFy\Partial\Partials\Notice\Notice as BaseNotice;
use tiFy\Wordpress\Contracts\Partial\PartialFactory as PartialFactoryContract;

class Notice extends BaseNotice implements PartialFactoryContract
{
    /**
     * @inheritDoc
     */
    public function boot(): void
    {
        add_action('init', function () {
            wp_register_style(
                'PartialNotice',
                asset()->url('partial/notice/css/styles.css'),
                [],
                180214
            );

            wp_register_script(
                'PartialNotice',
                asset()->url('partial/notice/js/scripts.js'),
                ['jquery'],
                180214,
                true
            );
        });
    }

    /**
     * @inheritDoc
     */
    public function enqueue(): PartialFactoryContract
    {
        wp_enqueue_style('PartialNotice');
        wp_enqueue_script('PartialNotice');

        return $this;
    }
}