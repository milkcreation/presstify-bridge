<?php declare(strict_types=1);

namespace tiFy\Mail;

use Exception;
use BadMethodCallException;
use tiFy\Contracts\Mail\{MailerDriver, MailView as MailViewContract};
use tiFy\View\Factory\PlatesFactory;

class MailView extends PlatesFactory implements MailViewContract
{
    /**
     * Liste des méthodes héritées.
     * @var array
     */
    protected $mixins = [];

    /**
     * @inheritDoc
     */
    public function __call($method, $parameters)
    {
        if (in_array($method, $this->mixins)) {
            try {
                return call_user_func_array([$this->engine->params('mail'), $method], $parameters);
            } catch (Exception $e) {
                throw new BadMethodCallException(
                    sprintf(
                        __('La méthode [%s] du champ n\'est pas disponible.', 'tify'),
                        $method
                    )
                );
            }
        } else {
            return parent::__call($method, $parameters);
        }
    }

    /**
     * @inheritDoc
     */
    public function driver(): MailerDriver
    {
        return $this->engine->params('mail')->mailer()->getDriver();
    }

    /**
     * @inheritDoc
     */
    public function param(string $key, $default = null)
    {
        return $this->engine->params('mail')->params($key, $default);
    }

    /**
     * @inheritDoc
     */
    public function linearizeContacts(array $contacts): array
    {
        array_walk($contacts, function (&$item) {
            $item = isset($item[1]) ? "{$item[1]} <{$item[0]}>" : "{$item[0]}";
        });

        return $contacts;
    }
}