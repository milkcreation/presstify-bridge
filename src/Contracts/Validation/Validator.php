<?php declare(strict_types=1);

namespace tiFy\Contracts\Validation;

use Psr\Container\ContainerInterface as Container;
use Respect\Validation\Validator as BaseValidator;
use tiFy\Validation\Rules\Password;

/**
 * @mixin BaseValidator
 * @method static Password password(array $args = [])
 */
interface Validator
{
    /**
     * Récupération de l'instance du conteneur d'injection de dépendances.
     *
     * @return Container|null
     */
    public function getContainer(): ?Container;
}