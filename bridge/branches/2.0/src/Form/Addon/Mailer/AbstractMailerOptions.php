<?php

namespace tiFy\Form\Addon\Mailer;

use tiFy\Contracts\Form\FormFactory;
use tiFy\Metabox\MetaboxWpOptionsController;
use tiFy\Form\Factory\ResolverTrait;

abstract class AbstractMailerOptions extends MetaboxWpOptionsController
{
    use ResolverTrait;

    /**
     * Liste des noms d'enregistement des options.
     *
     * @var array
     */
    protected $optionNames = [];

    /**
     * CONSTRUCTEUR.
     *
     * @return void
     */
    public function __construct(FormFactory $form)
    {
        $this->form = $form;
    }

    /**
     * @inheritDoc
     */
    public function boot()
    {
        $this->optionNames = $this->form()->addon('mailer')->get('option_names', []);
    }
}