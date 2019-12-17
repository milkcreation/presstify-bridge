<?php

namespace tiFy\Contracts\Kernel;

use League\Event\EventInterface;
use League\Event\ListenerInterface;

interface EventsListener extends ListenerInterface
{
    /**
     * Récupération de la fonction ou méthode d'écoute.
     *
     * @return callable
     */
    public function getCallback();

    /**
     * Traitement d'un événement.
     *
     * @param EventsItem $event Instance du controleur de l'événement à traiter.
     *
     * @return void
     */
    public function handle(EventInterface $event);

    /**
     * Vérifie d'intégrité de la fonction d'écoute.
     *
     * @param mixed $listener
     *
     * @return bool
     */
    public function isListener($listener);
}