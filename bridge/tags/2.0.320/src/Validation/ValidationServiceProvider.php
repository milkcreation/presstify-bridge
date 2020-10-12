<?php declare(strict_types=1);

namespace tiFy\Validation;

use tiFy\Container\ServiceProvider;
use tiFy\Validation\Rules\{Password as PasswordRule, Serialized as SerializedRule};

class ValidationServiceProvider extends ServiceProvider
{
    /**
     * Liste des noms de qualification des services fournis.
     * {@internal Permet le chargement différé des services qualifié.}
     * @var string[]
     */
    protected $provides = [
        'validator',
        'validator.rule.password',
        'validator.rule.serialized',
    ];

    /**
     * @inheritDoc
     */
    public function register(): void
    {
        $this->getContainer()->share('validator', function () {
            $rules = [
                'password'   => $this->getContainer()->get('validator.rule.password'),
                'serialized' => $this->getContainer()->get('validator.rule.serialized'),
            ];

            foreach ($rules as $name => $rule) {
                Validator::setCustom($name, $rule);
            }

            return new Validator();
        });

        $this->getContainer()->add('validator.rule.password', function () {
            return new PasswordRule();
        });

        $this->getContainer()->add('validator.rule.serialized', function () {
            return new SerializedRule();
        });
    }
}