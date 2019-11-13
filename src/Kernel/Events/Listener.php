<?php

namespace tiFy\Kernel\Events;

use League\Event\EventInterface;
use tiFy\Contracts\Kernel\EventsListener;

class Listener implements EventsListener
{
    /**
     * Fonction d'écoute.
     *
     * @var callable
     */
    protected $callback;

    /**
     * CONSTRUCTEUR.
     *
     * @param callable $callback Fonction ou méthode d'écoute.
     *
     * @return void
     */
    public function __construct(callable $callback)
    {
        $this->callback = $callback;
    }

    /**
     * Récupération de la fonction d'écoute.
     *
     * @return callable
     */
    public function getCallback()
    {
        return $this->callback;
    }

    /**
     * @inheritDoc
     */
    public function handle(EventInterface $event)
    {
        $args = func_get_args() ? current(array_slice(func_get_args(), 1)) : [];
        array_push($args, $event);

        return call_user_func_array($this->callback, $args);
    }

    /**
     * @inheritDoc
     */
    public function isListener($listener)
    {
        if ($listener instanceof self) {
            $listener = $listener->getCallback();
        }

        return $this->callback === $listener;
    }
}