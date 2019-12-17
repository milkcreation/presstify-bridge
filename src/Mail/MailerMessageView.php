<?php

namespace tiFy\Mail;

use tiFy\View\ViewController;

class MailerMessageView extends ViewController
{
    /**
     * Linéarisation des informations de contact.
     *
     * @param array $contact Informations de contact
     *
     * @return array
     */
    public function linearizeContacts($contacts)
    {
        array_walk($contacts, function (&$item) {
            $item = isset($item[1]) ? "{$item[1]} <{$item[0]}>" : "{$item[0]}";
        });
        return $contacts;
    }
}