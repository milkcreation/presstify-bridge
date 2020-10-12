<?php declare(strict_types=1);

namespace tiFy\Contracts\Mail;

use tiFy\Contracts\View\PlatesFactory;

interface MailView extends PlatesFactory
{
    /**
     * Délégation d'appel des méthodes de l'instance du gestionnaire de mail associée.
     *
     * @param string $name Nom de qualification de la méthode.
     * @param array $args Liste des paramètres passés en arguments à la méthode.
     *
     * @return mixed
     */
    public function __call($method, $parameters);

    /**
     * Récupération de l'instance du pilote.
     *
     * @return MailerDriver
     */
    public function driver(): MailerDriver;

    /**
     * Récupération de paramètre.
     *
     * @param string $key
     * @param mixed $default
     *
     * @return mixed
     */
    public function param(string $key, $default = null);

    /**
     * Linéarisation des informations de contact.
     *
     * @param array $contacts Informations de contact
     *
     * @return array
     */
    public function linearizeContacts(array $contacts): array;
}