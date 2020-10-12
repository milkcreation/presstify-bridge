<?php declare(strict_types=1);

namespace tiFy\Wordpress\Session;

use tiFy\Contracts\Session\Store as BaseStoreContract;
use tiFy\Session\Store as BaseStore;

class Store extends BaseStore
{
    /**
     * @inheritDoc
     */
    public function prepare(): BaseStoreContract
    {
        if (defined('WP_SETUP_CONFIG')) {
            return $this;
        } else {
            add_action('wp_logout', [$this, 'destroy']);

            return parent::prepare();
        }
    }
}