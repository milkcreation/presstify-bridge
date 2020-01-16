<?php declare(strict_types=1);

namespace tiFy\Form\Concerns;

use LogicException;
use tiFy\Contracts\Form\AddonFactory;
use tiFy\Contracts\Form\FormFactory;

trait AddonAwareTrait
{
    /**
     * Instance de l'addon de formulaire associé.
     * @var AddonFactory|null
     */
    protected $addon;

    /**
     * Instance du formulaire associé.
     * @var FormFactory|null
     */
    protected $form;

    /**
     * Récupération de l'instance de l'addon associé.
     *
     * @return AddonFactory
     */
    public function addon(): AddonFactory
    {
        if ($this->addon instanceof AddonFactory) {
            return $this->addon;
        }

        throw new LogicException(
            sprintf(
                __('Aucun addon de formulaire n\'est associé à la classe [%s].', 'tify'),
                class_info($this)->getShortName()
            )
        );
    }

    /**
     * Récupération de l'instance du formulaire associé.
     *
     * @return FormFactory
     */
    public function form(): FormFactory
    {
        return $this->addon()->form();
    }

    /**
     * Définition de l'addon associé.
     *
     * @param AddonFactory $addon
     *
     * @return $this
     */
    public function setAddon(AddonFactory $addon): self
    {
        $this->addon = $addon;

        return $this;
    }
}