<?php declare(strict_types=1);

namespace tiFy\Mail\Driver;

use PHPMailer\PHPMailer\PHPMailer as PhpMailerLib;
use tiFy\Contracts\Mail\MailerDriver;
use Exception;

class PhpMailerDriver implements MailerDriver
{
    /**
     * Instance du pilote de traitement des emails.
     * @var PhpMailerLib
     */
    protected $phpMailer;

    /**
     * CONSTRUCTEUR.
     *
     * @param PhpMailerLib|null $phpmailer Instance de PHPMailer.
     *
     * @return void
     */
    public function __construct(?PhpMailerLib $phpmailer = null)
    {
        $this->phpMailer = $phpmailer ?: new PhpMailerLib();
    }

    /**
     * {@inheritDoc}
     *
     * @throws Exception
     */
    public function addAttachment(string $path): MailerDriver
    {
        $args = func_get_args();

        $this->phpMailer->addAttachment(...$args);

        return $this;
    }

    /**
     * {@inheritDoc}
     *
     * @throws Exception
     */
    public function addBcc(string $email, string $name = ''): MailerDriver
    {
        $this->phpMailer->addBCC($email, $name);

        return $this;
    }

    /**
     * {@inheritDoc}
     *
     * @throws Exception
     */
    public function addCc(string $email, string $name = ''): MailerDriver
    {
        $this->phpMailer->addCC($email, $name);

        return $this;
    }

    /**
     * {@inheritDoc}
     *
     * @throws Exception
     */
    public function addReplyTo(string $email, string $name = ''): MailerDriver
    {
        $this->phpMailer->addReplyTo($email, $name);

        return $this;
    }

    /**
     * {@inheritDoc}
     *
     * @throws Exception
     */
    public function addTo(string $email, string $name = ''): MailerDriver
    {
        $this->phpMailer->addAddress($email, $name);

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function error()
    {
        return $this->phpMailer->ErrorInfo;
    }

    /**
     * @inheritDoc
     */
    public function getBcc(): array
    {
        return $this->phpMailer->getBccAddresses();
    }

    /**
     * @inheritDoc
     */
    public function getCharset(): string
    {
        return $this->phpMailer->CharSet;
    }

    /**
     * @inheritDoc
     */
    public function getCc(): array
    {
        return $this->phpMailer->getCcAddresses();
    }

    /**
     * @inheritDoc
     */
    public function getContentType(): string
    {
        return $this->phpMailer->ContentType;
    }

    /**
     * @inheritDoc
     */
    public function getHeaders(): array
    {
        return explode($this->phpMailer::getLE(), $this->phpMailer->createHeader());
    }

    /**
     * @inheritDoc
     */
    public function getHtml(): string
    {
        return $this->phpMailer->Body;
    }

    /**
     * @inheritDoc
     */
    public function getReplyTo(): array
    {
        return $this->phpMailer->getReplyToAddresses();
    }

    /**
     * @inheritDoc
     */
    public function getSubject(): string
    {
        return $this->phpMailer->Subject;
    }

    /**
     * @inheritDoc
     */
    public function getText(): string
    {
        return $this->phpMailer->AltBody;
    }

    /**
     * @inheritDoc
     */
    public function getTo(): array
    {
        return $this->phpMailer->getToAddresses();
    }

    /**
     * {@inheritDoc}
     *
     * @throws Exception
     */
    public function prepare(): bool
    {
        return $this->phpMailer->preSend();
    }

    /**
     * {@inheritDoc}
     *
     * @throws Exception
     */
    public function send(): bool
    {
        return $this->phpMailer->send();
    }

    /**
     * @inheritDoc
     */
    public function setCharset(string $charset = 'utf-8'): MailerDriver
    {
        $this->phpMailer->CharSet = $charset;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setContentType(string $content_type = 'multipart/alternative'): MailerDriver
    {
        $this->phpMailer->ContentType = in_array($content_type, ['text/html', 'text/plain', 'multipart/alternative'])
            ? $content_type : 'multipart/alternative';

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setEncoding(string $encoding): MailerDriver
    {
        $this->phpMailer->Encoding = in_array($encoding, ['8bit', '7bit', 'binary', 'base64', 'quoted-printable'])
            ? $encoding : '8bit';

        return $this;
    }

    /**
     * {@inheritDoc}
     *
     * @throws Exception
     */
    public function setFrom(string $email, string $name = ''): MailerDriver
    {
        $this->phpMailer->setFrom($email, $name);

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setHtml(string $message): MailerDriver
    {
        $this->phpMailer->Body = $message;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setSubject(string $subject = ''): MailerDriver
    {
        $this->phpMailer->Subject = $subject;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setText(string $text): MailerDriver
    {
        $this->phpMailer->AltBody = $text;

        return $this;
    }
}