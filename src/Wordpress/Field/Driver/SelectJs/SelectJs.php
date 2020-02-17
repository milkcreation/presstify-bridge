<?php declare(strict_types=1);

namespace tiFy\Wordpress\Field\Driver\SelectJs;

use tiFy\Field\Driver\SelectJs\SelectJs as BaseSelectJs;
use tiFy\Wordpress\Contracts\Field\FieldDriver as FieldDriverContract;

class SelectJs extends BaseSelectJs implements FieldDriverContract
{
    /**
     * @inheritDoc
     */
    public function boot(): void
    {
        parent::boot();

        add_action('wp_ajax_field_select_js', [$this, 'wpAjaxResponse']);
        add_action('wp_ajax_nopriv_field_select_js', [$this, 'wpAjaxResponse']);
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