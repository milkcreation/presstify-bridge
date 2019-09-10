<?php declare(strict_types=1);

namespace tiFy\Wordpress\Field\Fields\PasswordJs;

use tiFy\Contracts\Field\FieldFactory as BaseFieldFactoryContract;
use tiFy\Field\Fields\PasswordJs\PasswordJs as BasePasswordJs;
use tiFy\Wordpress\Contracts\Field\FieldFactory as FieldFactoryContract;

class PasswordJs extends BasePasswordJs implements FieldFactoryContract
{
    /**
     * @inheritDoc
     */
    public function boot(): void
    {
        parent::boot();

        add_action('init', function () {
            wp_register_style(
                'FieldPasswordJs',
                asset()->url('field/crypted/css/styles.css'),
                ['dashicons'],
                180519
            );

            wp_register_script(
                'FieldPasswordJs',
                asset()->url('field/crypted/js/scripts.js'),
                ['jquery'],
                180519,
                true
            );

            add_action('wp_ajax_password_js_decrypt', [$this, 'wpAjaxDecrypt']);
            add_action('wp_ajax_nopriv_password_js_decrypt', [$this, 'wpAjaxDecrypt']);
        });
    }

    /**
     * @inheritDoc
     */
    public function enqueue(): FieldFactoryContract
    {
        wp_enqueue_style('FieldPasswordJs');
        wp_enqueue_script('FieldPasswordJs');

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function parse(): BaseFieldFactoryContract
    {
        parent::parse();

        return $this;
    }
}