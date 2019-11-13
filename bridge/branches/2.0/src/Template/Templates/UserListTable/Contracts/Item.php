<?php declare(strict_types=1);

namespace tiFy\Template\Templates\UserListTable\Contracts;

use tiFy\Template\Templates\ListTable\Contracts\{Item as BaseItemContract};
use tiFy\Wordpress\Query\QueryUser;

/**
 * @mixin QueryUser
 */
interface Item extends BaseItemContract
{
    /**
     * Délégation d'appel des méthodes de l'object associé.
     *
     * @param string $name Nom de qualification de la méthode.
     * @param array $args Liste des paramètres passés en arguments à la méthode.
     *
     * @return mixed
     */
    public function __call($name, $args);
}