<?php declare(strict_types=1);

namespace tiFy\Mail;

use Html2Text\Html2Text;
use Pelago\Emogrifier;
use tiFy\Contracts\Mail\LibraryAdapter;
use tiFy\Contracts\Mail\Mailer as MailerContract;
use tiFy\Contracts\Mail\MailQueue;
use tiFy\Support\ParamsBag;
use tiFy\View\Engine\Engine as ViewEngine;

class Mailer extends ParamsBag implements MailerContract
{
    /**
     * Instance du pilote de traitement de mail.
     * @var LibraryAdapter
     */
    protected $lib;

    /**
     * Liste des paramètres d'expédition du mail.
     * @var array
     */
    protected $params = [];

    /**
     * Instance du controleur de gabarit d'affichage.
     * @var ViewEngine
     */
    protected $viewer;

    /**
     * Traitement récursif d'une liste de pièces jointes.
     *
     * @param string|string[]|array $attachments
     *
     * @return array
     */
    private function _parseAttachments($attachments)
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
     * {@internal "{email}"|"{name} {email}"|["{email1}", ["{name2} {email2}"]]}
     *
     * @return array
     */
    private function _parseContacts($contacts)
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
     * Traitement de remplacement des variables d'environnement.
     *
     * @param string $output
     * @param array Liste des variables d'environnement personnalisées.
     * @param string $regex Format de détection des variables.
     *
     * @return string
     *
     * private function _parseMergeVars($output, $vars = [], $regex = '\*\|(.*?)\|\*')
     * {
     * $vars = array_merge([
     * 'SITE:URL'         => site_url('/'),
     * 'SITE:NAME'        => get_bloginfo('name'),
     * 'SITE:DESCRIPTION' => get_bloginfo('description'),
     * ], $vars);
     *
     * $callback = function ($matches) use ($vars) {
     * if (!isset($matches[1])) :
     * return $matches[0];
     * elseif (isset($vars[$matches[1]])) :
     * return $vars[$matches[1]];
     * endif;
     *
     * return $matches[0];
     * };
     *
     * $output = preg_replace_callback('/' . $regex . '/', $callback, $output);
     *
     * return $output;
     * }
     */

    /**
     * Traitement des élements texte de composition du message.
     *
     * @param string|string[] $body {body}|[{html_body},{plain_body}]
     * @param string|string[] $header {header}|[{html_header},{plain_header}]
     * @param string|string[] $footer {footer}|[{html_footer},{plain_footer}]
     *
     * @return array
     */
    private function _parseMessage($body, $header = '', $footer = '')
    {
        $header = $this->_parseTextParts($header);
        $footer = $this->_parseTextParts($footer);
        $message = $this->_parseTextParts($body);

        array_walk($message, function (&$item, $key) use ($header, $footer) {
            $item = $header[$key] . $item . $footer[$key];
        });
        return $message;
    }

    /**
     * Traitement des éléments de texte composant le message (body|header|footer).
     *
     * @param string|array $part
     *
     * @return array
     */
    private function _parseTextParts($part)
    {
        if (is_string($part)) {
            $part = [$part, (new Html2Text($part))->getText()];
        } elseif (is_array($part)) {
            $html = $part[0] ?? '';
            $text = $part[1] ?? (new Html2Text($html))->getText();

            $part = [$html, $text];
        }
        return $part;
    }

    /**
     * Traitement de la liste des paramètres de configuration.
     *
     * @param array $params Liste des paramètres de configuration.
     *
     * @return $this
     */
    private function _parseParams($params = [])
    {
        $this->set($params)->parse();

        $lib = $this->getLib();

        foreach ($this->keys() as $key) {
            switch ($key) {
                default :
                    break;
                case 'from' :
                case 'to' :
                case 'reply-to' :
                case 'bcc' :
                case 'cc' :
                    $this->set($key, $this->_parseContacts($this->get($key, [])));
                    break;
                case 'attachments' :
                    $this->set($key, $this->_parseAttachments($this->get($key, [])));
                    break;
            }
        }

        call_user_func_array([$lib, 'setFrom'], current($this->get('from', [])));

        foreach ($this->get('to', []) as $contact) {
            call_user_func_array([$lib, 'addTo'], $contact);
        }

        foreach ($this->get('reply-to', []) as $contact) {
            call_user_func_array([$lib, 'addReplyTo'], $contact);
        }

        foreach ($this->get('bcc', []) as $contact) {
            call_user_func_array([$lib, 'addBcc'], $contact);
        }

        foreach ($this->get('cc', []) as $contact) {
            call_user_func_array([$lib, 'addCc'], $contact);
        }

        foreach ($this->get('attachments', []) as $attachment) {
            call_user_func_array([$lib, 'addAttachment'], $attachment);
        }

        $lib->setCharset($this->get('charset'));

        $lib->setEncoding($this->get('encoding'));

        $lib->setContentType($this->get('content_type'));

        $lib->setSubject($this->get('subject'));

        $body = $this->get('body') ?? [
                (string)$this->viewer('default', $this->all()),
                sprintf(__('Ceci est un test d\'envoi de mail depuis le site %s', 'tify'),
                    get_bloginfo('blogname')) . "\n\n" .
                __('Si ce mail, vous est parvenu c\'est qu\'il vous a été expédié depuis le site : ') . "\n" .
                site_url('/') . "\n\n" .
                __('Néanmoins, il pourrait s\'agir d\'une erreur. Si vous n\'êtes pas concerné par cet e-mail, ',
                    'tify') . "\n" .
                __('vous pouvez prendre contact avec l\'administrateur du site à cette adresse : ', 'tify') . "\n" .
                get_option('admin_email') . "\n\n" .
                __('Merci de votre compréhension', 'tify'),
            ];

        $message = $this->_parseMessage($body, $this->get('header'), $this->get('footer'));

        $html = (string)$this->viewer('message', array_merge($this->all(), ['message' => $message[0]]));
        $html = $this->get('inline_css') ? (new Emogrifier($html))->emogrify() : $html;
        $plain = $message[1];

        switch ($this->get('content_type')) {
            case 'multipart/alternative' :
                call_user_func([$lib, 'setBody'], $html);
                call_user_func([$lib, 'setAlt'], $plain);
                break;
            case 'text/html' :
                call_user_func([$lib, 'setBody'], $html);
                break;
            case 'text/plain' :
                call_user_func([$lib, 'setBody'], $plain);
                break;
        }

        $this->params = $this->all() + compact('message', 'html', 'plain');

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function debug($params = [])
    {
        $this->_parseParams($params);

        echo ($this->getLib()->prepare())
            ? $this->viewer('debug', array_merge($this->params, ['headers' => $this->getLib()->getHeaders()]))
            : $this->getLib()->error();
        exit;
    }

    /**
     * @inheritdoc
     */
    public function defaults()
    {
        return array_merge(config('mail', []), [
            'to'           => [],
            'from'         => [],
            'reply-to'     => [],
            'bcc'          => [],
            'cc'           => [],
            'attachments'  => [],
            'header'       => '',
            'footer'       => '',
            'subject'      => sprintf(__('Test d\'envoi de mail depuis le site %s', 'tify'),
                get_bloginfo('blogname')),
            'charset'      => get_bloginfo('charset'),
            'encoding'     => '8bit',
            'content_type' => 'multipart/alternative',
            'inline_css'   => true,
            'vars'         => [],
            'viewer'       => [],
        ]);
    }

    /**
     * @inheritdoc
     */
    public function getLib()
    {
        if (is_null($this->lib)) {
            $this->lib = app()->get('mailer.library');
        }
        return $this->lib;
    }

    /**
     * @inheritdoc
     */
    public function queue($params = [], $date = 'now', $extras = [])
    {
        $this->_parseParams($params);

        if ($res = $this->getLib()->prepare()) {
            $this->lib = null;

            /** @var MailQueue $queue */
            $queue = app()->get('mail.queue');
            return $queue->add($this->params, $date, $extras);
        }
        return 0;
    }

    /**
     * @inheritdoc
     */
    public function send($params = [])
    {
        $this->_parseParams($params);

        if ($res = $this->getLib()->send()) {
            $this->lib = null;
        }
        return $res;
    }

    /**
     * @inheritdoc
     */
    public function viewer(?string $view = null, array $data = [])
    {
        if (is_null($this->viewer)) {
            $this->viewer = app()->get('mailer.viewer', $this->pull('viewer', []));
        }

        if (func_num_args() === 0) {
            return $this->viewer;
        }

        return $this->viewer->render($view, $data);
    }
}