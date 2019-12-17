<?php declare(strict_types=1);

namespace tiFy\Wordpress\Form\Addon\Mailer;

use tiFy\Form\Addon\Mailer\Mailer as BaseMailer;
use tiFy\Wordpress\Proxy\Field;

class Mailer extends BaseMailer
{
    /**
     * @inheritDoc
     */
    public function boot(): void
    {
        parent::boot();

        if (
            $this->params('enqueue_scripts') &&
            ($this->params('admin.confirmation') || $this->params('admin.notification'))
        ) {
            add_action('admin_enqueue_scripts', function () {
                Field::get('repeater')->enqueue();
                Field::get('toggle-switch')->enqueue();
            });
        }
    }

    /**
     * @inheritDoc
     */
    public function defaultsParams(): array
    {
        return array_merge(parent::defaultsParams(), ['enqueue_scripts' => false]);
    }
}