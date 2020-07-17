<?php declare(strict_types=1);

namespace tiFy\Contracts\Validation;

use Respect\Validation\Validatable;

interface Rule extends Validatable
{
    /**
     * Définition de la liste des arguments.
     *
     * @param array ...$args Liste dynamique des arguments.
     *
     * @return static
     */
    public function setArgs(...$args): Rule;
}