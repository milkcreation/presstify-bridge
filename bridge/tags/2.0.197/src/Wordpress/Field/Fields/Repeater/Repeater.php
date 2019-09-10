<?php declare(strict_types=1);

namespace tiFy\Wordpress\Field\Fields\Repeater;

use tiFy\Contracts\Field\FieldFactory as BaseFieldFactoryContract;
use tiFy\Field\Fields\Repeater\Repeater as BaseRepeater;
use tiFy\Wordpress\Contracts\Field\FieldFactory as FieldFactoryContract;

class Repeater extends BaseRepeater implements FieldFactoryContract
{
    /**
     * @inheritDoc
     */
    public function boot(): void
    {
        add_action('init', function () {
            wp_register_style(
                'FieldRepeater',
                asset()->url('/field/repeater/css/styles.css'),
                [is_admin() ? 'tiFyAdmin' : ''],
                170421
            );

            wp_register_script(
                'FieldRepeater',
                asset()->url('/field/repeater/js/scripts.js'),
                ['jquery', 'jquery-ui-widget', 'jquery-ui-sortable'],
                170421,
                true
            );

            add_action('wp_ajax_field_repeater', [$this, 'wpAjaxResponse']);
            add_action('wp_ajax_nopriv_field_repeater', [$this, 'wpAjaxResponse']);
        });
    }

    /**
     * @inheritDoc
     */
    public function enqueue(): FieldFactoryContract
    {
        wp_enqueue_style('FieldRepeater');
        wp_enqueue_script('FieldRepeater');

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function parse(): BaseFieldFactoryContract
    {
        parent::parse();

        $this->set([
            'attrs.data-options.ajax.url' => admin_url('admin-ajax.php', 'relative'),
            'attrs.data-options.ajax.data.action' => 'field_repeater',
            'attrs.data-options.ajax.data._ajax_nonce' => wp_create_nonce('FieldRepeater' . $this->getId()),
        ]);

        return $this;
    }

    /**
     * Réponse HTTP Ajax.
     *
     * @return void
     */
    public function wpAjaxResponse()
    {
        check_ajax_referer('FieldRepeater' . request()->get('_id'));

        wp_send_json(parent::xhrResponse());
    }
}