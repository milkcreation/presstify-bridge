<?php declare(strict_types=1);

namespace tiFy\Form;

use InvalidArgumentException;
use LogicException;
use tiFy\Contracts\Form\{
    AddonFactory as AddonFactoryContract,
    FormFactory
};
use tiFy\Support\ParamsBag;

class AddonFactory implements AddonFactoryContract
{
    /**
     * Instance du formulaire associé.
     * @var FormFactory|null
     */
    protected $form;

    /**
     * Nom de qualification.
     * @var string
     */
    protected $name = '';

    /**
     * Instance du gestionnaire de paramètres
     * @var ParamsBag|null
     */
    protected $params;

    /**
     * @inheritDoc
     */
    public function boot(): void {}

    /**
     * @inheritDoc
     */
    public function defaultsParams(): array
    {
        return [];
    }

    /**
     * @inheritDoc
     */
    public function defaultsFormOptions(): array
    {
        return [];
    }

    /**
     * @inheritDoc
     */
    public function defaultsFieldOptions(): array
    {
        return [];
    }

    /**
     * @inheritDoc
     */
    public function form(): FormFactory
    {
        if ($this->form instanceof FormFactory) {
            return $this->form;
        }

        throw new LogicException(__('Aucune instance de formulaire n\'est associé à l\'addon.', 'tify'));
    }

    /**
     * @inheritDoc
     */
    public function name(): string
    {
        return $this->name ? : class_info($this)->getKebabName();
    }

    /**
     * @inheritDoc
     */
    public function params($key = null, $default = null)
    {
        if (!$this->params instanceof ParamsBag) {
            $this->params = (new ParamsBag())->set($this->defaultsParams());
            $this->parseParams();
        }

        if (is_null($key)) {
            return $this->params;
        } elseif (is_string($key)) {
            return $this->params->get($key, $default);
        } elseif (is_array($key)) {
            return $this->params->set($key);
        } else {
            throw new InvalidArgumentException(
                __('Le traitement des paramètres de l\'addon de formulaire est invalide.', 'tify')
            );
        }
    }

    /**
     * @inheritDoc
     */
    public function parseParams(): void {}

    /**
     * @inheritDoc
     */
    public function setForm(FormFactory $form): AddonFactoryContract
    {
        $this->form = $form;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setName(string $name): AddonFactoryContract
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setParams(array $params): AddonFactoryContract
    {
        $this->params($params);

        return $this;
    }
}