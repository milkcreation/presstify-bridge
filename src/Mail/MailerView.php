<?php declare(strict_types=1);

namespace tiFy\Mail;

use tiFy\View\Factory\PlatesFactory;

class MailerView extends PlatesFactory
{
    /**
     * Linéarisation des informations de contact.
     *
     * @param array $contact Informations de contact
     *
     * @return array
     */
    public function linearizeContacts(array $contacts): array
    {
        array_walk($contacts, function (&$item) {
            $item = isset($item[1]) ? "{$item[1]} <{$item[0]}>" : "{$item[0]}";
        });

        return $contacts;
    }
}