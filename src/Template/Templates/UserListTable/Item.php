<?php declare(strict_types=1);

namespace tiFy\Template\Templates\UserListTable;

use tiFy\Template\Templates\ListTable\{
    Contracts\Item as BaseItemContract,
    Item as BaseItem
};
use tiFy\Template\Templates\UserListTable\Contracts\Item as ItemContract;
use tiFy\Wordpress\Contracts\QueryUser as QueryUserContract;
use tiFy\Wordpress\Query\QueryUser;

/**
 * @mixin QueryUser
 */
class Item extends BaseItem implements ItemContract
{
    /**
     * Instance du gabarit d'affichage.
     * @var Factory
     */
    protected $factory;

    /**
     * Objet de délégation d'appel des méthodes de la classe.
     * @var QueryUserContract|null
     */
    protected $delegate;

    /**
     * @inheritDoc
     */
    public function parse(): BaseItemContract
    {
        parent::parse();

        if (is_null($this->delegate)) {
            $this->setDelegate(QueryUser::createFromId($this->getKeyValue()));
        }

        return $this;
    }
}