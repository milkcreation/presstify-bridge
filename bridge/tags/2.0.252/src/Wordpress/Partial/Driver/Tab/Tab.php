<?php declare(strict_types=1);

namespace tiFy\Wordpress\Partial\Driver\Tab;

use tiFy\Partial\Driver\Tab\Tab as BaseTab;
use tiFy\Wordpress\Contracts\Partial\PartialDriver as PartialDriverContract;

class Tab extends BaseTab implements PartialDriverContract
{
    /**
     * @inheritDoc
     */
    public function xhrSetTab()
    {
        check_ajax_referer('tiFyPartialTab');

        if (!$key = request()->post('key')) {
            wp_die(0);
        }

        $raw_key = base64_decode($key);
        if (!$raw_key = maybe_unserialize($raw_key)) {
            wp_die(0);
        } else {
            $raw_key = maybe_unserialize($raw_key);
        };

        $success = update_user_meta(get_current_user_id(), 'tab' . $raw_key['_screen_id'], $raw_key['name']);

        wp_send_json(compact('success'));
    }
}