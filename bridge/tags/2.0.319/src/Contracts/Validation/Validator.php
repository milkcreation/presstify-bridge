<?php declare(strict_types=1);

namespace tiFy\Contracts\Validation;

use Psr\Container\ContainerInterface as Container;
use Respect\Validation\Exceptions\ComponentException;
use Respect\Validation\Rules\AllOf;
use tiFy\Validation\Rules\Password;

/**
 * @method static Password password(array $args = [])
 *
 * @mixin AllOf
 */
interface Validator
{
    /**
     * Creates a new Validator instance with a rule that was called on the static method.
     *
     * @param string $ruleName
     * @param mixed[] $arguments
     *
     * @return static
     *
     * @throws ComponentException
     */
    public static function __callStatic(string $ruleName, array $arguments): Validator;

    /**
     * Create a new rule by the name of the method and adds the rule to the chain.
     *
     * @param string $ruleName
     * @param mixed[] $arguments
     *
     * @return static
     *
     * @throws ComponentException
     */
    public function __call(string $ruleName, array $arguments): Validator;

    /**
     * Create instance validator.
     *
     * @return static
     */
    public static function create(): Validator;

    /**
     * Récupération de l'instance du conteneur d'injection de dépendances.
     *
     * @return Container|null
     */
    public function getContainer(): ?Container;
}