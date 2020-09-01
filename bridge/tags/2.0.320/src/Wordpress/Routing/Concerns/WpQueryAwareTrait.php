<?php declare(strict_types=1);

namespace tiFy\Wordpress\Routing\Concerns;

use tiFy\Wordpress\Contracts\Routing\WpQueryAwareTrait as WpQueryAwareTraitContract;

/**
 * @mixin WpQueryAwareTraitContract
 */
trait WpQueryAwareTrait
{
    /**
     * Activation de la requête de récupération des éléments native de Wordpress.
     * @var boolean
     */
    protected $wpQuery = false;

    /**
     * @inheritDoc
     */
    public function isWpQuery(): bool
    {
        return $this->wpQuery;
    }

    /**
     * {@inheritDoc}
     *
     * @return WpQueryAwareTrait
     */
    public function setWpQuery(bool $active = false): WpQueryAwareTraitContract
    {
        $this->wpQuery = $active;

        return $this;
    }
}