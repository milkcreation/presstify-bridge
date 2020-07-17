<?php declare(strict_types=1);

namespace tiFy\Mail;

use tiFy\Contracts\{Http\Response as ResponseContract, Mail\Mail as MailContract, Mail\Mailer};
use tiFy\Http\Response;
use tiFy\Support\ParamsBag;
use tiFy\Contracts\View\Engine as ViewEngine;

class Mail implements MailContract
{
    /**
     * Instance des données du message.
     * @var ParamsBag
     */
    public $data;

    /**
     * Instance du gestionnaire de mail.
     * @var Mailer|null
     */
    protected $mailer;

    /**
     * Instance des paramètres.
     * @var ParamsBag
     */
    protected $params;

    /**
     * Instance du controleur de gabarit d'affichage.
     * @var ViewEngine
     */
    protected $view;

    /**
     * @inheritDoc
     */
    public function __toString(): string
    {
        return $this->render();
    }

    /**
     * @inheritDoc
     */
    public function data($key, $value = null): MailContract
    {
        if (!$this->data instanceof ParamsBag) {
            $this->data = new ParamsBag();
        }

        if (!is_array($key)) {
            $key = [$key => $value];
        }

        $this->data->set($key);

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function defaults(): array
    {
        return $this->mailer()::getDefaults();
    }

    /**
     * @inheritDoc
     */
    public function debug(): string
    {
        $this->mailer()->create($this);

        return $this->mailer()->prepare()
            ? $this->view('debug') : $this->mailer()->getDriver()->error();
    }

    /**
     * @inheritDoc
     */
    public function mailer(): ?Mailer
    {
        return $this->mailer ?? \tiFy\Support\Proxy\Mail::getInstance();
    }

    /**
     * @inheritDoc
     */
    public function params($key = null, $default = null)
    {
        if (!$this->params instanceof ParamsBag) {
            $this->params = (new ParamsBag())->set($this->defaults());
        }

        if (is_string($key)) {
            return $this->params->get($key, $default);
        } elseif (is_array($key)) {
            return $this->params->set($key);
        } else {
            return $this->params;
        }
    }

    /**
     * @inheritDoc
     */
    public function queue($date = 'now', array $params = []): int
    {
        $this->mailer()->create($this);

        return $this->mailer()->prepare()->addQueue($this, $date, $params);
    }

    /**
     * @inheritDoc
     */
    public function send(): bool
    {
        $this->mailer()->create($this);

        return $this->mailer()->prepare()->getDriver()->send();
    }

    /**
     * @inheritDoc
     */
    public function setMailer(Mailer $mailer): MailContract
    {
        $this->mailer = $mailer;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setParams(array $params): MailContract
    {
        $this->params($params);

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function render(): string
    {
        return (string)$this->mailer()->prepare()->getDriver()->getHtml();
    }

    /**
     * @inheritDoc
     */
    public function response(): ResponseContract
    {
        return new Response($this->render());
    }

    /**
     * @inheritDoc
     */
    public function view(string $name, array $data = []): string
    {
        if (is_null($this->view)) {
            if ($container = $this->mailer()->getContainer()) {
                $this->view = $container->get('mail.view', [$this]);
            }
        }

        return $this->view->render($name, array_merge($this->data ? $this->data->all() : [], $data));
    }
}
