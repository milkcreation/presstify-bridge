<?php

namespace tiFy\Contracts\Kernel;

use League\Event\EmitterInterface;
use League\Event\ListenerInterface;

interface EventsManager extends EmitterInterface
{
    /**
     * Déclaration d'un événement.
     * @see http://event.thephpleague.com/2.0/emitter/basic-usage/
     *
     * @param string $name Identifiant de qualification de l'événement.
     * @param callable|ListenerInterface $listener Fonction anonyme ou Classe d'écoute de l'événement.
     * @param int $priority Priorité de traitement.
     *
     * @return static
     */
    public function listen($name, $listener, $priority = 0);

    /**
     * Alias de déclaration d'un événement.
     * @see self::listen
     *
     * @param string $name Identifiant de qualification de l'événement.
     * @param callable|ListenerInterface $listener Fonction anonyme ou Classe d'écoute de l'événement.
     * @param int $priority Priorité de traitement.
     *
     * @return static
     */
    public function on($name, $listener, $priority = 0);

    /**
     * Déclenchement d'un événement.
     * @see http://event.thephpleague.com/2.0/events/classes/
     *
     * @param string|object $event Identifiant de qualification de l'événement.
     * @param array $args Liste des variables passées en argument à la fonction d'écoute.
     *
     * @return null|EventsItem
     */
    public function trigger($event, $args = []);
}