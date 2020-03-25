<?php declare(strict_types=1);

namespace tiFy\Mail;

use tiFy\Contracts\{Http\Response as ResponseContract, Mail\Mail as MailContract, Mail\Mailer};
use tiFy\Http\Response;
use tiFy\Support\ParamsBag;

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
    public function debug(): string
    {
        return $this->mailer->prepare()->getDriver()->prepare()
            ? $this->mailer->viewer('debug') : $this->mailer->getDriver()->error();
    }

    /**
     * @inheritDoc
     */
    public function params($key = null, $default = null)
    {
        if (!$this->params instanceof ParamsBag) {
            $this->params = new ParamsBag();
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
        return $this->mailer->prepare()->addQueue($this, $date, $params);
    }

    /**
     * @inheritDoc
     */
    public function send(): bool
    {
        return $this->mailer->prepare()->getDriver()->send();
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
    public function render(): string
    {
        return (string)$this->mailer->prepare()->getDriver()->getHtml();
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
    public function view(string $name): string
    {
        return $this->mailer->viewer($name, $this->data ? $this->data->all() : []);
    }
}
