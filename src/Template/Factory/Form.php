<?php declare(strict_types=1);

namespace tiFy\Template\Factory;

use tiFy\Contracts\Template\FactoryForm as FactoryFormContract;
use tiFy\Contracts\Template\TemplateFactory;
use tiFy\Support\ParamsBag;

class Form extends ParamsBag implements FactoryFormContract
{
    use FactoryAwareTrait;

    /**
     * Instance du gabarit d'affichage.
     * @var TemplateFactory
     */
    protected $factory;

    /**
     * Liste des champs cachés.
     * @var string[]
     */
    protected $hidden = [];

    /**
     * @inheritDoc
     */
    public function getHidden(?string $key = null, string $default = '')
    {
        return is_null($key) ? $this->hidden : ($this->hidden[$key] ?? '');
    }

    /**
     * @inheritDoc
     */
    public function setHidden($key, string $value = ''): FactoryFormContract
    {
        if (is_string($key)) {
            $this->hidden[$key] = $value;
        } elseif (is_array($key)) {
            $this->hidden = $key;
        }

        return $this;
    }
}