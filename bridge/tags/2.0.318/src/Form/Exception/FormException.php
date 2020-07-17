<?php declare(strict_types=1);

namespace tiFy\Form\Exception;

use Exception;
use tiFy\Contracts\Form\FormFactory;

class FormException extends Exception
{
    /**
     * Instance du formulaire associé.
     * @var FormFactory|null
     */
    protected $form;

    /**
     * Identifiant de qualification du formulaire.
     * @var string|null
     */
    protected $formName;

    /**
     * Récupération de l'instance du champ associé.
     *
     * @return FormFactory
     */
    public function getForm(): ?FormFactory
    {
        return $this->form;
    }

    /**
     * Récupération de l'identifiant de qualification du champ associé.
     *
     * @return string
     */
    public function getFormName(): string
    {
        return ($form = $this->getForm()) ? $form->name() : $this->formName;
    }

    /**
     * Définition de l'instance du champ associé.
     *
     * @param FormFactory $form
     *
     * @return static
     */
    public function setForm(FormFactory $form): self
    {
        $this->form = $form;

        return $this;
    }

    /**
     * Définition de l'identifiant de qualification du champ associé.
     *
     * @param string $name
     *
     * @return static
     */
    public function setFormName(string $name): self
    {
        $this->formName = $name;

        return $this;
    }
}