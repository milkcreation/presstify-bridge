<?php declare(strict_types=1);

namespace tiFy\Form;

use LogicException;
use tiFy\Contracts\{Form\FormFactory, Form\AddonFactory};
use tiFy\Metabox\{MetaboxDriver, MetaboxView};
use tiFy\Support\Proxy\View;

abstract class AddonMetaboxDriver extends MetaboxDriver
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
            __('Aucune instance d\'addon de formulaire n\'est associé à la boîte de saisie.', 'tify')
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

    /**
     * @inheritDoc
     */
    public function viewer(?string $view = null, array $data = [])
    {
        if (!$this->viewer) {
            $this->viewer = View::getPlatesEngine(array_merge([
                'directory' => "{$this->form()->viewer()->getDirectory()}/addon/{$this->addon()->name()}/metabox",
                'factory'   => MetaboxView::class,
                'metabox'   => $this
            ], $this->get('viewer', [])));
        }

        if (func_num_args() === 0) {
            return $this->viewer;
        }

        return $this->viewer->render($view, $data);
    }
}