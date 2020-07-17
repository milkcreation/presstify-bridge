<?php declare(strict_types=1);

namespace tiFy\Form\Factory;

use tiFy\Http\RedirectResponse;
use tiFy\Contracts\Form\{FactoryRequest, FormFactory};
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
     * Clé d'indice de la protection CSRF.
     * @var string
     */
    protected $tokenKey = '_token';

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
    public function getToken(): string
    {
        return Req::input($this->tokenKey, '');
    }

    /**
     * @inheritDoc
     */
    public function handle(): ?RedirectResponse
    {
        if ($this->handled) {
            return null;
        } else {
            $this->handled = true;
        }

        if (!$this->verify()) {
            return null;
        }

        $this->events('request.submitted', [&$this]);

        $this->prepare()->validate();

        if ($this->notices()->has('error')) {
            $this->reset();
            return null;
        }

        $this->events('request.proceed', [&$this]);

        if ($this->notices()->has('error')) {
            $this->reset();
            return null;
        }

        $this->events('request.successed', [&$this]);

        return $this->redirect();
    }

    /**
     * @inheritDoc
     */
    public function prepare(): FactoryRequest
    {
        $this->form()->prepare();

        switch ($method = $this->form()->getMethod()) {
            case 'get' :
                $method = 'query';
                break;
            case 'post' :
                $method = 'post';
                break;

        }

        $values = call_user_func([Req::getInstance(), $method]);

        foreach ($this->fields() as $field) {
            if (isset($values[$field->getName()])) {
                $this->set($field->getSlug(),  $values[$field->getName()]);
            }
        }

        $this->parse()->events('request.prepared');

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function redirect(): RedirectResponse
    {
        return new RedirectResponse($this->getRedirectUrl());
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

            if ($this->form()->getMethod() === 'get') {
                $without = ['_token'];
                foreach ($this->fields() as $field) {
                    array_push($without, $field->getName());
                }
                $uri = $uri->without($without);
            }

            $uri = $uri->with(['success' => $this->form()->name()]);

            $url = $uri->render() . $this->form()->getAnchor();
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

            $this->events('request.validate.field.' . $field->getType(), [&$field]);
            $this->events('request.validate.field', [&$field]);
        }
        $this->events('request.validated', [&$this]);

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function verify(): bool
    {
        return !!wp_verify_nonce($this->getToken(), 'Form' . $this->form()->name());
    }
}