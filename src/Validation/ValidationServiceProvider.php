<?php declare(strict_types=1);

namespace tiFy\Validation;

use tiFy\Container\ServiceProvider;

class ValidationServiceProvider extends ServiceProvider
{
    /**
     * Liste des noms de qualification des services fournis.
     * {@internal Permet le chargement différé des services qualifié.}
     * @var string[]
     */
    protected $provides = [
        'validator',
        'validator.rule.base64',
        'validator.rule.password',
        'validator.rule.serialized',
    ];

    /**
     * @inheritDoc
     */
    public function register()
    {
        $this->getContainer()->share('validator', function () {
            return new Validator($this->getContainer(), [
                'base64'     => $this->getContainer()->get('validator.rule.base64'),
                'password'   => $this->getContainer()->get('validator.rule.password'),
                'serialized' => $this->getContainer()->get('validator.rule.serialized'),
            ]);
        });

        $this->getContainer()->add('validator.rule.base64', function () {
            return new Rules\Base64();
        });

        $this->getContainer()->add('validator.rule.password', function () {
            return new Rules\Password();
        });


        $this->getContainer()->add('validator.rule.serialized', function () {
            return new Rules\Serialized();
        });
    }
}