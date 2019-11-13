<?php declare(strict_types=1);

namespace tiFy\Wordpress\Form;

use tiFy\Contracts\Form\FormManager;
use tiFy\Contracts\Form\FormFactory;
use tiFy\Wordpress\Contracts\Form as FormContract;
use tiFy\Wordpress\Form\Addon\Mailer\Mailer;

class Form implements FormContract
{
    /**
     * Instance du controleur de gestion des formulaires.
     * @var FormManager
     */
    protected $manager;

    /**
     * CONSTRUCTEUR.
     *
     * @param FormManager $manager Instance du controleur de gestion des formulaires.
     *
     * @return void
     */
    public function __construct(FormManager $manager)
    {
        $this->manager = $manager;

        foreach (config('form', []) as $name => $attrs) {
            $this->manager->register($name, $attrs);
        }

        add_action('wp', function () {
            foreach ($this->manager->all() as $form) {
                /* @var FormFactory $form */
                if ($form->isAuto()) {
                    $this->manager->current($form);
                    $form->prepare()->request()->handle();
                    $this->manager->reset();
                }
            }
        });

        add_action('init', function () {
            if (is_admin()) {
                foreach ($this->manager->all() as $form) {
                    /* @var FormFactory $form */
                    if ($form->isAuto()) {
                        $this->manager->current($form);
                        $form->prepare();
                        $this->manager->reset();
                    }
                }
            }
        });

        $this->registerOverride();
    }

    /**
     * Déclaration des injections de dépendance de surchage.
     *
     * @return void
     */
    public function registerOverride(): void
    {
        app()->add('form.addon.mailer', function () {
            return new Mailer();
        });
    }
}