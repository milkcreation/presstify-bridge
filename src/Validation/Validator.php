<?php declare(strict_types=1);

namespace tiFy\Validation;

use Psr\Container\ContainerInterface as Container;
use Respect\Validation\{Exceptions\ComponentException, Validator as BaseValidator};
use tiFy\Contracts\Validation\{Rule, Validator as ValidatorContract};

/**
 * @method static Rules\Base64 base64()
 * @method static Rules\Password password(array $args = [])
 * @method static Rules\Serialized serialized(bool $strict = true)
 */
class Validator extends BaseValidator implements ValidatorContract
{
    /**
     * Liste des régles personnalisées.
     * @var Rule[]
     */
    protected static $customs = [];

    /**
     * Instance du conteneur d'injection de dépendances.
     * @var Container|null
     */
    protected $container;

    /**
     * CONSTRUCTEUR.
     *
     * @param Container|null $container Instance du conteneur d'injection de dépendances.
     * @param array $rules Liste des régles personnalisées.
     *
     * @return void
     */
    public function __construct(?Container $container = null, array $rules = [])
    {
        $this->container = $container;

        foreach ($rules as $key => $rule) {
            if ($rule instanceof Rule) {
                self::$customs[$key] = $rule->setValidator($this)->setName($key);
            }
        }

        parent::__construct($rules);
    }

    /**
     * {@inheritDoc}
     *
     * @throws ComponentException
     */
    public static function buildRule($ruleSpec, $arguments = [])
    {
        if (is_string($ruleSpec) && isset(self::$customs[$ruleSpec])) {
            return self::buildRule(self::$customs[$ruleSpec]->setArgs(...$arguments), $arguments);
        } else {
            return parent::buildRule($ruleSpec, $arguments);
        }
    }

    /**
     * @inheritDoc
     */
    public function getContainer(): ?Container
    {
        return $this->container;
    }
}