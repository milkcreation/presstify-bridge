<?php declare(strict_types=1);

namespace tiFy\Form\Factory;

use tiFy\Contracts\Form\FactoryRequest;
use tiFy\Contracts\Form\FormFactory;
use tiFy\Support\{ParamsBag, Proxy\Request as Req, Proxy\Url};

class Request extends ParamsBag implements FactoryRequest
{
    use ResolverTrait;

    /**
     * Indicateur de traitement effectué.
     * @var boolean
     */
    protected $handled = false;

    /**
     * Url de redirection.
     * @var string
     */
    protected $redirect;

    /**
     * CONSTRUCTEUR.
     *
     * @param FormFactory $form Instance du contrôleur de formulaire.
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
    public function getRedirectUrl(): string
    {
        if (is_null($this->redirect)) {
            $this->setRedirectUrl($this->get('_http_referer', Req::header('referer')));
        }

        $this->events('request.redirect', [&$this->redirect]);

        return $this->redirect;
    }

    /**
     * @inheritDoc
     */
    public function handle(): void
    {
        if ($this->handled) {
            return;
        } else {
            $this->handled = true;
        }

        if (!$this->verify()) {
            return;
        }

        $this->prepare()->validate();

        if ($this->notices()->has('error')) {
            $this->reset();
            return;
        }
        $this->events('request.submit', [&$this]);

        if ($this->notices()->has('error')) {
            $this->reset();
            return;
        }
        $this->events('request.success', [&$this]);

        wp_redirect($this->getRedirectUrl());
    }

    /**
     * @inheritDoc
     */
    public function prepare(): FactoryRequest
    {
        $this->form()->prepare();

        $values = call_user_func([Req::getInstance(), $this->form()->getMethod()]);

        foreach ($this->fields() as $field) {
            if (isset($values[$field->getName()])) {
                $this->set($field->getSlug(),  $values[$field->getName()]);
            }
        }

        $this->parse()->events('request.prepare');

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function reset(): FactoryRequest
    {
        foreach ($this->fields() as $field) {
            if (!$field->supports('transport')) {
                $field->resetValue();
            }
        }
        $this->events('request.reset');

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setRedirectUrl(string $url, bool $raw = false): FactoryRequest
    {
        if (!$raw) {
            $uri = Url::set($url);
            $uri->with(['success' => $this->form()->name()]);

            $url = (string)$uri;
            $url .= $this->option('anchor') && ($id = $this->form()->get('attrs.id')) ? "#{$id}" : '';
        }

        $this->redirect = $url;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function validate(): FactoryRequest
    {
        foreach ($this->fields() as $name => $field) {
            $check = true;

            $field->setValue($this->get($field->getSlug()));

            if ($field->getRequired('check')) {
                $value = $field->getValue($field->getRequired('raw', true));

                if (!$check = $this->validation()->call(
                    $field->getRequired('call'), $value,
                    $field->getRequired('args', []))
                ) {
                    $this->notices()->add('error', sprintf($field->getRequired('message'), $field->getTitle()), [
                        'type'  => 'field',
                        'field' => $field->getSlug(),
                        'test'  => 'required',
                    ]);
                }
            }

            if ($check) {
                if ($validations = $field->get('validations', [])) {
                    $value = $field->getValue($field->getRequired('raw', true));

                    foreach ($validations as $validation) {
                        if (!$this->validation()->call($validation['call'], $value, $validation['args'])) {
                            $this->notices()->add('error', sprintf($validation['message'], $field->getTitle()), [
                                'field' => $field->getSlug(),
                            ]);
                        }
                    }
                }
            }

            $this->events('request.validation.field.' . $field->getType(), [&$field]);
            $this->events('request.validation.field', [&$field]);
        }
        $this->events('request.validation', [&$this]);

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function verify(): bool
    {
        return !!wp_verify_nonce(Req::input('_token', ''), 'Form' . $this->form()->name());
    }
}