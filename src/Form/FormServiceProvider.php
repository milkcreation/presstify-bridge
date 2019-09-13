<?php

namespace tiFy\Form;

use tiFy\Container\ServiceProvider;
use tiFy\Contracts\Form\FormFactory as FormFactoryContract;
use tiFy\Contracts\Form\FactoryField as FactoryFieldContract;
use tiFy\Form\Addon\AjaxSubmit\AjaxSubmit as AddonAjaxSubmit;
use tiFy\Form\Addon\CookieSession\CookieSession as AddonCookieSession;
use tiFy\Form\Addon\Mailer\Mailer as AddonMailer;
use tiFy\Form\Addon\Mailer\MailerOptionsConfirmation as AddonMailerOptionsConfirmation;
use tiFy\Form\Addon\Mailer\MailerOptionsNotification as AddonMailerOptionsNotification;
use tiFy\Form\Addon\Preview\Preview as AddonPreview;
use tiFy\Form\Addon\Record\Record as AddonRecord;
use tiFy\Form\Addon\User\User as AddonUser;
use tiFy\Form\Button\Submit\Submit as ButtonSubmit;
use tiFy\Form\Factory\Addons as FactoryAddons;
use tiFy\Form\Factory\Buttons as FactoryButtons;
use tiFy\Form\Factory\Events as FactoryEvents;
use tiFy\Form\Factory\Field as FactoryField;
use tiFy\Form\Factory\Fields as FactoryFields;
use tiFy\Form\Factory\Group as FactoryGroup;
use tiFy\Form\Factory\Groups as FactoryGroups;
use tiFy\Form\Factory\Notices as FactoryNotices;
use tiFy\Form\Factory\Options as FactoryOptions;
use tiFy\Form\Factory\Request as FactoryRequest;
use tiFy\Form\Factory\Session as FactorySession;
use tiFy\Form\Factory\Validation as FactoryValidation;
use tiFy\Form\Factory\View as FactoryView;
use tiFy\Form\Field\Html\Html as FieldHtml;
use tiFy\Form\Field\Recaptcha\Recaptcha as FieldRecaptcha;
use tiFy\Form\Field\Tag\Tag as FieldTag;

class FormServiceProvider extends ServiceProvider
{
    /**
     * Liste des noms de qualification des services fournis.
     * @internal requis. Tous les noms de qualification de services à traiter doivent être renseignés.
     * @var string[]
     */
    protected $provides = [
        'form',
        'form.addon',
        'form.addon.ajax-submit',
        'form.addon.cookie-session',
        'form.addon.mailer',
        'form.addon.mailer.options-confirmation',
        'form.addon.mailer.options-notification',
        'form.addon.preview',
        'form.addon.record',
        'form.addon.user',
        'form.button',
        'form.button.submit',
        'form.factory',
        'form.factory.addons',
        'form.factory.buttons',
        'form.factory.events',
        'form.factory.field',
        'form.factory.fields',
        'form.factory.group',
        'form.factory.groups',
        'form.factory.notices',
        'form.factory.options',
        'form.factory.request',
        'form.factory.session',
        'form.factory.validation',
        'form.field',
        'form.field.html',
        'form.field.recaptcha',
        'form.field.tag'
    ];

    /**
     * @inheritdoc
     */
    public function register()
    {
        $this->registerManager();
        $this->registerAddon();
        $this->registerButton();
        $this->registerField();
        $this->registerFactory();
    }

    /**
     * Déclaration des controleurs associé aux addons de formulaire.
     *
     * @return void
     */
    public function registerAddon()
    {
        $this->getContainer()->add('form.addon', function ($name, $attrs, FormFactoryContract $form) {
            return new AddonController($name, $attrs, $form);
        });

        $this->getContainer()->add('form.addon.ajax-submit', function ($name, $attrs, FormFactoryContract $form) {
            return new AddonAjaxSubmit($name, $attrs, $form);
        });

        $this->getContainer()->add('form.addon.cookie-session', function ($name, $attrs, FormFactoryContract $form) {
            return new AddonCookieSession($name, $attrs, $form);
        });

        $this->getContainer()->add('form.addon.mailer', function ($name, $attrs, FormFactoryContract $form) {
            return new AddonMailer($name, $attrs, $form);
        });

        $this->getContainer()->add('form.addon.mailer.options-confirmation', function (FormFactoryContract $form) {
            return new AddonMailerOptionsConfirmation($form);
        });

        $this->getContainer()->add('form.addon.mailer.options-notification', function (FormFactoryContract $form) {
            return new AddonMailerOptionsNotification($form);
        });

        $this->getContainer()->add('form.addon.preview', function ($name, $attrs, FormFactoryContract $form) {
            return new AddonPreview($name, $attrs, $form);
        });

        $this->getContainer()->add('form.addon.record', function ($name, $attrs, FormFactoryContract $form) {
            return new AddonRecord($name, $attrs, $form);
        });

        $this->getContainer()->add('form.addon.user', function ($name, $attrs, FormFactoryContract $form) {
            return new AddonUser($name, $attrs, $form);
        });
    }

    /**
     * Déclaration des controleurs associés aux boutons de formulaire.
     *
     * @return void
     */
    public function registerButton()
    {
        $this->getContainer()->add('form.button', function ($name, $attrs, FormFactoryContract $form) {
                return new ButtonController($name, $attrs, $form);
        });

        $this->getContainer()->add('form.button.submit', function ($name, $attrs, FormFactoryContract $form) {
                return new ButtonSubmit($name, $attrs, $form);
        });
    }

    /**
     * Déclaration des controleurs de contruction d'un formulaire.
     *
     * @return void
     */
    public function registerFactory()
    {
        $this->getContainer()->add('form.factory', function () {
            return new FormFactory();
        });

        $this->getContainer()->add('form.factory.addons', function ($addons, FormFactoryContract $form) {
            return new FactoryAddons($addons, $form);
        });

        $this->getContainer()->add('form.factory.buttons', function ($buttons, FormFactoryContract $form) {
            return new FactoryButtons($buttons, $form);
        });

        $this->getContainer()->add('form.factory.events', function ($events, FormFactoryContract $form) {
            return new FactoryEvents($events, $form);
        });

        $this->getContainer()->add('form.factory.field', function ($name, $attrs, FormFactoryContract $form) {
            return new FactoryField($name, $attrs, $form);
        });

        $this->getContainer()->add('form.factory.fields', function ($fields, FormFactoryContract $form) {
            return new FactoryFields($fields, $form);
        });

        $this->getContainer()->add('form.factory.group', function ($attrs) {
            return new FactoryGroup($attrs);
        });

        $this->getContainer()->add('form.factory.groups', function ($groups, FormFactoryContract $form) {
            return new FactoryGroups($groups, $form);
        });

        $this->getContainer()->add('form.factory.notices', function ($notices, FormFactoryContract $form) {
            return new FactoryNotices($notices, $form);
        });

        $this->getContainer()->add('form.factory.options', function ($options, FormFactoryContract $form) {
            return new FactoryOptions($options, $form);
        });

        $this->getContainer()->add('form.factory.request', function (FormFactoryContract $form) {
            return new FactoryRequest($form);
        });

        $this->getContainer()->add('form.factory.session', function (FormFactoryContract $form) {
            return new FactorySession($form);
        });

        $this->getContainer()->add('form.factory.validation', function (FormFactoryContract $form) {
            return new FactoryValidation($form);
        });

        $this->getContainer()->add('form.factory.viewer', function (FormFactoryContract $form) {
            $directory = form()->resourcesDir('/views');
            $override_dir = (($override_dir = $form->get('viewer.override_dir')) && is_dir($override_dir))
                ? $override_dir
                : $directory;

            return view()
                ->setDirectory($directory)
                ->setController(FactoryView::class)
                ->setOverrideDir($override_dir)
                ->set('form', $form);
        });
    }

    /**
     * Déclaration des controleurs associés aux champs de formulaire.
     *
     * @return void
     */
    public function registerField()
    {
        $this->getContainer()->add('form.field', function ($name, FactoryFieldContract $field) {
            return new FieldController($name, $field);
        });

        /* $this->getContainer()->add('form.field.captcha', function (FactoryFieldContract $field) {
                return new FieldCaptcha($field);
        });*/

        $this->getContainer()->add('form.field.html', function ($name, FactoryFieldContract $field) {
                return new FieldHtml($name, $field);
        });

        $this->getContainer()->add('form.field.recaptcha', function ($name, FactoryFieldContract $field) {
                return new FieldRecaptcha($name, $field);
        });

        $this->getContainer()->add('form.field.tag', function ($name, FactoryFieldContract $field) {
                return new FieldTag($name, $field);
        });
    }

    /**
     * Déclaration du controleur de gestion des formulaire.
     *
     * @return void
     */
    public function registerManager()
    {
        $this->getContainer()->share('form', function () {
            return new FormManager($this->getContainer());
        });
    }
}