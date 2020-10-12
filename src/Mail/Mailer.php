<?php declare(strict_types=1);

namespace tiFy\Mail;

use Exception;
use Html2Text\Html2Text;
use Pelago\Emogrifier\CssInliner;
use Psr\Container\ContainerInterface as Container;
use Symfony\Component\DomCrawler\Crawler;
use tiFy\Contracts\Mail\{
    Mail as MailContract,
    MailerDriver,
    Mailer as MailerContract,
    MailerQueue as MailerQueueContract
};

class Mailer implements MailerContract
{
    /**
     * Liste des attributs de configuration par défaut.
     * @var array
     */
    protected static $defaults = [];

    /**
     * Instance du conteneur d'injection de dépendance.
     * @var Container|null
     */
    protected $container;

    /**
     * Instance du pilote d'expédition des mails.
     * @var MailerDriver
     */
    protected $driver;

    /**
     * Instance de l'email
     * @var Mail|null
     */
    protected $mail;

    /**
     * Instance du gestionnaire de mise en file.
     * @var MailerQueueContract
     */
    protected $queue;

    /**
     * Traitement récursif d'une liste de pièces jointes.
     *
     * @param string|string[]|array $attachments
     *
     * @return array
     */
    private function _parseAttachments($attachments): array
    {
        $output = (func_num_args() === 2) ? func_get_arg(1) : [];

        if (is_string($attachments)) {
            if (is_file($attachments)) {
                $output[] = [$attachments];
            } elseif (is_array($attachments)) {
                foreach ($attachments as $a) {
                    if (is_string($a)) {
                        $output = $this->_parseAttachments($a, $output);
                    } elseif (is_array($a)) {
                        $filename = $a[0] ?? null;

                        if ($filename && is_file($filename)) {
                            $output[] = $a;
                        }
                    }
                }
            }
        }

        return $output;
    }

    /**
     * Traitement récursif d'une liste de contacts.
     *
     * @param string|string[]|array $contacts Liste de contact.
     * {@internal "{{ email:string }}"|"{{ name:string }} {{ email:string }}"|["{{ email1:string }}", ["{{ name2:string
     *     }} {{ email2:string }}"]] }
     *
     * @return array
     */
    private function _parseContacts($contacts): array
    {
        $output = (func_num_args() === 2) ? func_get_arg(1) : [];

        if (is_string($contacts)) {
            $email = '';
            $name = '';
            $bracket_pos = strpos($contacts, '<');
            if ($bracket_pos !== false) {
                if ($bracket_pos > 0) {
                    $name = substr($contacts, 0, $bracket_pos - 1);
                    $name = str_replace('"', '', $name);
                    $name = trim($name);
                }

                $email = substr($contacts, $bracket_pos + 1);
                $email = str_replace('>', '', $email);
                $email = trim($email);
            } elseif (!empty($contacts)) {
                $email = $contacts;
            }
            if ($email && is_email($email)) {
                $output[] = [$email, $name];
            }
        } elseif (is_array($contacts)) {
            if ((count($contacts) === 2) &&
                isset($contacts[0]) && isset($contacts[1]) &&
                is_string($contacts[0]) && is_string($contacts[1])
            ) {
                if (is_email($contacts[0]) && !is_email($contacts[1])) {
                    $output[] = array_map('trim', $contacts);
                }
            } else {
                foreach ($contacts as $c) {
                    if (is_string($c)) {
                        $output = $this->_parseContacts($c, $output);
                    } elseif (is_array($c)) {
                        $email = $c[0] ?? null;
                        $name = $c[1] ?? '';

                        if ($email && is_email($email)) {
                            $output[] = [$email, $name];
                        }
                    }
                }
            }
        }

        return $output;
    }

    /**
     * @inheritDoc
     */
    public static function getDefaults(): array
    {
        return array_merge([
            'to'           => [],
            'from'         => [],
            'reply-to'     => [],
            'bcc'          => [],
            'cc'           => [],
            'attachments'  => [],
            'html'         => '',
            'plain'        => '',
            'data'         => [],
            'content'      => [],
            'subject'      => __('Test d\'envoi de mail', 'tify'),
            'charset'      => 'utf-8',
            'encoding'     => '8bit',
            'content_type' => 'multipart/alternative',
            'css'          => file_get_contents(__DIR__ .'/Resources/assets/css/styles.css'),
            'inline_css'   => true,
            'vars'         => [],
            'viewer'       => [],
        ], static::$defaults);
    }

    /**
     * @inheritDoc
     */
    public static function setDefaults(array $attrs = []): void
    {
        static::$defaults = array_merge(static::$defaults, $attrs);
    }

    /**
     * @inheritDoc
     */
    public function addQueue(MailContract $mail, $date = 'now', array $params = []): int
    {
        return $this->getDriver()->prepare() ? $this->getQueue()->add($mail, $date, $params) : 0;
    }

    /**
     * @inheritDoc
     */
    public function clearDriver(): MailerContract
    {
        $this->driver = null;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function create($attrs = null): MailContract
    {
        if (is_null($attrs) && $this->mail instanceof Mail) {
            $attrs = $this->mail;
        }

        if ($attrs instanceof Mail) {
            return $this->mail = $attrs->setMailer($this);
        } else {
            $this->clearDriver();

            return $this->mail = (new Mail())->setMailer($this)->setParams($attrs);
        }
    }

    /**
     * @inheritDoc
     */
    public function debug($attrs = null): void
    {
        echo $this->create($attrs)->debug();
        exit;
    }

    /**
     * @inheritDoc
     */
    public function getContainer(): ?Container
    {
        return $this->container;
    }

    /**
     * @inheritDoc
     */
    public function getDriver(): MailerDriver
    {
        if (is_null($this->driver)) {
            $driver = !is_null($this->getContainer()) ? $this->getContainer()->get('mailer.driver') : null;
            $this->driver = $driver ?: new Driver\PhpMailerDriver();
        }

        return $this->driver;
    }

    /**
     * @inheritDoc
     */
    public function getQueue(): MailerQueueContract
    {
        if (is_null($this->queue)) {
            $queue = !is_null($this->getContainer()) ? $this->getContainer()->get('mailer.queue') : null;
            $this->queue = $queue ?: new MailerQueue();
        }

        return $this->queue->setMailer($this);
    }

    /**
     * @inheritDoc
     */
    public function prepare(): MailerContract
    {
        $mail = $this->create();

        if ($from = $mail->params('from')) {
            $contact = $this->_parseContacts($from)[0];
            $this->getDriver()->setFrom(...$contact);
        }

        if ($to = $mail->params('to')) {
            $contacts = $this->_parseContacts($to);
            foreach ($contacts as $c) {
                $this->getDriver()->addTo(...$c);
            }
        }

        if ($replyTo = $mail->params('reply-to')) {
            $contacts = $this->_parseContacts($replyTo);
            foreach ($contacts as $c) {
                $this->getDriver()->addReplyTo(...$c);
            }
        }

        if ($bcc = $mail->params('bcc')) {
            $contacts = $this->_parseContacts($bcc);
            foreach ($contacts as $c) {
                $this->getDriver()->addBcc(...$c);
            }
        }

        if ($cc = $mail->params('cc')) {
            $contacts = $this->_parseContacts($cc);
            foreach ($contacts as $c) {
                $this->getDriver()->addCc(...$c);
            }
        }

        if ($attachments = $mail->params('attachments', [])) {
            $files = $this->_parseAttachments($attachments);
            foreach ($files as $path) {
                $this->getDriver()->addAttachment($path);
            }
        }

        if ($charset = $mail->params('charset')) {
            $this->getDriver()->setCharset($charset);
        }

        if ($encoding = $mail->params('encoding')) {
            $this->getDriver()->setEncoding($encoding);
        }

        if ($content_type = $mail->params('content_type')) {
            $this->getDriver()->setContentType($content_type);
        }

        if ($subject = $mail->params('subject')) {
            $this->getDriver()->setSubject($subject);
        }

        if ($data = $mail->params('data', [])) {
            $mail->data(array_merge(static::getDefaults()['data'] ?? [], $data));
        }

        if (!$html = $mail->params('html')) {
            if ($mail->params('content')) {
                if ($body = $mail->params('content.body', true)) {
                    $body = is_string($body) ? $body : $mail->view('html/body');
                }

                if ($header = $mail->params('content.header', true)) {
                    $header = is_string($header) ? $header : $mail->view('html/header');
                }

                if ($footer = $mail->params('content.footer', true)) {
                    $footer = is_string($footer) ? $footer : $mail->view('html/footer');
                }

                $html = $mail->view('html/content', compact('body', 'header', 'footer'));
            } else {
                $html = $mail->params('text') ?: $mail->view('html/message');
            }
        }

        if (!$text = $mail->params('text')) {
            $text = (new Html2Text($html ?: $mail->view('text/message')))->getText();
        }

        if (!(new Crawler($html))->filter('head')->count()) {
            $html = $mail->view('html/wrap-html', ['html' => $html]);
        }

        if ($css = $mail->params('inline_css')) {
            $css = is_string($css) ?: '';

            try {
                $html = CssInliner::fromHtml($html)->inlineCss($css)->render();
            } catch (Exception $e) {
                unset($e);
            }
        }

        switch ($mail->params('content_type')) {
            case 'multipart/alternative' :
                $this->getDriver()->setHtml($html);
                $this->getDriver()->setText($text);
                break;
            case 'text/html' :
                $this->getDriver()->setHtml($text);
                break;
            case 'text/plain' :
                $this->getDriver()->setText($text);
                break;
        }

        return $this;
    }

    /**
     * @inheritDoc
     *
     * @todo
     */
    public function queue($attrs = null, $date = 'now', array $params = []): int
    {
        return $this->create($attrs)->queue($date, $params);
    }

    /**
     * @inheritDoc
     */
    public function send($attrs = null): bool
    {
        return $this->create($attrs)->send();
    }

    /**
     * @inheritDoc
     */
    public function setContainer(Container $container): MailerContract
    {
        $this->container = $container;

        return $this;
    }
}