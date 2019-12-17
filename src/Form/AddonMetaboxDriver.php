<?php declare(strict_types=1);

namespace tiFy\Form;

use LogicException;
use tiFy\Contracts\{Form\FormFactory, Form\AddonFactory};
use tiFy\Metabox\{MetaboxDriver, MetaboxView};

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
            $defaultDir = "{$this->form()->viewer()->getDirectory()}/addon/{$this->addon()->name()}/metabox";
            $fallbackDir = $this->get('viewer.override_dir') ?: $defaultDir;

            $this->viewer = view()
                ->setDirectory($defaultDir)
                ->setOverrideDir($fallbackDir)
                ->setController(MetaboxView::class)
                ->setParam('metabox', $this);
        }

        if (func_num_args() === 0) {
            return $this->viewer;
        }

        return $this->viewer->make("_override::{$view}", $data);
    }
}