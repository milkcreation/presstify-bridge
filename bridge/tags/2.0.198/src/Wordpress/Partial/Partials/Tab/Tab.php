<?php declare(strict_types=1);

namespace tiFy\Wordpress\Partial\Partials\Tab;

use tiFy\Partial\Partials\Tab\Tab as BaseTab;
use tiFy\Wordpress\Contracts\Partial\PartialFactory as PartialFactoryContract;

class Tab extends BaseTab implements PartialFactoryContract
{
    /**
     * @inheritDoc
     */
    public function boot(): void
    {
        add_action('init', function () {
            wp_register_style(
                'PartialTab',
                asset()->url('partial/tab/css/styles.css'),
                [],
                170704
            );

            wp_register_script(
                'PartialTab',
                asset()->url('partial/tab/js/scripts.js'),
                ['jquery-ui-widget'],
                170704,
                true
            );
        });
    }

    /**
     * @inheritDoc
     */
    public function enqueue(): PartialFactoryContract
    {
        wp_enqueue_style('PartialTab');
        wp_enqueue_script('PartialTab');

        return $this;
    }

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