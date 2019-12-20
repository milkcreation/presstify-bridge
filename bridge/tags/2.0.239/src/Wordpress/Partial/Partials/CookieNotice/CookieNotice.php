<?php declare(strict_types=1);

namespace tiFy\Wordpress\Partial\Partials\CookieNotice;

use tiFy\Partial\Partials\CookieNotice\CookieNotice as BaseCookieNotice;
use tiFy\Wordpress\Contracts\Partial\{CookieNotice as CookieNoticeContract, PartialFactory as PartialFactoryContract};

class CookieNotice extends BaseCookieNotice implements CookieNoticeContract
{
    /**
     * @inheritDoc
     */
    public function boot(): void
    {
        parent::boot();

        add_action('init', function () {
            wp_register_script(
                'PartialCookieNotice',
                asset()->url('partial/cookie-notice/js/scripts.js'),
                ['PartialNotice'],
                170626,
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
        wp_enqueue_script('PartialCookieNotice');

        return $this;
    }
}