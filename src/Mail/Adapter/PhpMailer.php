<?php

namespace tiFy\Mail\Adapter;

use PHPMailer\PHPMailer\PHPMailer as LibPhpMailer;
use tiFy\Contracts\Mail\LibraryAdapter;
use Exception;

class PhpMailer implements LibraryAdapter
{
    /**
     * Instance du pilote de traitement des emails.
     * @var LibPhpMailer
     */
    protected $lib;

    /**
     * CONSTRUCTEUR.
     *
     * @param LibPhpMailer $phpmailer Instance du pilote de traitement des emails.
     *
     * @return void
     */
    public function __construct(LibPhpMailer $phpmailer)
    {
        $this->lib = $phpmailer;
    }

    /**
     * @inheritdoc
     */
    public function addAttachment($path)
    {
        call_user_func_array([$this->lib, 'addAttachment'], func_get_args());

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function addBcc($email, $name = '')
    {
        $this->lib->addBCC($email, $name);

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function addCc($email, $name = '')
    {
        $this->lib->addCC($email, $name);

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function addReplyTo($email, $name = '')
    {
        $this->lib->addReplyTo($email, $name);

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function addTo($email, $name = '')
    {
        $this->lib->addAddress($email, $name);

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function error()
    {
        return $this->lib->ErrorInfo;
    }

    /**
     * @inheritdoc
     */
    public function getBcc()
    {
        return $this->lib->getBccAddresses();
    }

    /**
     * @inheritdoc
     */
    public function getCc()
    {
        return $this->lib->getCcAddresses();
    }

    /**
     * @inheritdoc
     */
    public function getHeaders()
    {
        return explode($this->lib::getLE(), $this->lib->createHeader());
    }

    /**
     * @inheritdoc
     */
    public function getReplyTo()
    {
        return $this->lib->getReplyToAddresses();
    }

    /**
     * @inheritdoc
     */
    public function getTo()
    {
        return $this->lib->getToAddresses();
    }

    /**
     * @inheritdoc
     */
    public function prepare()
    {
        try {
            return $this->lib->preSend();
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * @inheritdoc
     */
    public function send()
    {
        try {
            return $this->lib->send();
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * @inheritdoc
     */
    public function setAlt($text)
    {
        $this->lib->AltBody = $text;

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function setBody($message)
    {
        $this->lib->Body = $message;

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function setCharset($charset = 'utf-8')
    {
        $this->lib->CharSet = $charset;

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function setContentType($content_type = 'multipart/alternative')
    {
        $this->lib->ContentType = in_array($content_type, ['text/html', 'text/plain', 'multipart/alternative'])
            ? $content_type
            : 'multipart/alternative';

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function setEncoding($encoding)
    {
        $this->lib->Encoding = in_array($encoding, ['8bit', '7bit', 'binary', 'base64', 'quoted-printable'])
            ? $encoding
            : '8bit';

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function setFrom($email, $name = '')
    {
        try {
            $this->lib->setFrom($email, $name);
        } catch (Exception $e) {
            return $this;
        }
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function setSubject($subject = '')
    {
        $this->lib->Subject = $subject;

        return $this;
    }
}