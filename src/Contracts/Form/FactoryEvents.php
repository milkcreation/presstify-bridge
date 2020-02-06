<?php

namespace tiFy\Contracts\Form;

use tiFy\Contracts\Kernel\EventsListener;

interface FactoryEvents extends FactoryResolver
{
    /**
     * Déclaration d'un événement.
     *
     * @param string $name Identifiant de qualification de l'événement.
     * @param callable|EventsListener $listener Fonction anonyme ou Classe de traitement de l'événement.
     * @param int $priority Priorité de traitement.
     *
     * @return $this
     */
    public function listen($name, $listener, $priority = 0);

    /**
     * Déclenchement d'un événement.
     *
     * @param string $name Nom de qualification de l'événement.
     * @param array $args Variable passées en argument à la fonction d'écoute.
     *
     * @return void
     */
    public function trigger($name, $args = []);
}