<?php declare(strict_types=1);

namespace tiFy\Wordpress\Field\Fields\SelectJs;

use tiFy\Field\Fields\SelectJs\SelectJs as BaseSelectJs;
use tiFy\Wordpress\Contracts\Field\FieldFactory as FieldFactoryContract;

class SelectJs extends BaseSelectJs implements FieldFactoryContract
{
    /**
     * @inheritDoc
     */
    public function boot(): void
    {
        add_action('init', function () {
            add_action(
                'wp_ajax_field_select_js',
                [$this, 'wp_ajax']
            );

            add_action(
                'wp_ajax_nopriv_field_select_js',
                [$this, 'wp_ajax']
            );

            wp_register_style(
                'FieldSelectJs',
                asset()->url('field/select-js/css/styles.css'),
                [],
                171218
            );

            wp_register_script(
                'FieldSelectJs',
                asset()->url('field/select-js/js/scripts.js'),
                ['jquery-ui-widget', 'jquery-ui-sortable'],
                171218,
                true
            );
        });
    }

    /**
     * @inheritDoc
     */
    public function enqueue(): FieldFactoryContract
    {
        partial('spinner')->enqueue('three-bounce');
        wp_enqueue_style('FieldSelectJs');
        wp_enqueue_script('FieldSelectJs');

        return $this;
    }

    /**
     * Récupération de la liste des résultats via Ajax.
     *
     * @return void
     */
    public function wpAjaxResponse()
    {
        check_ajax_referer('FieldSelectJs' . request()->post('_id'));

        wp_send_json($this->xhrResponse());
    }
}