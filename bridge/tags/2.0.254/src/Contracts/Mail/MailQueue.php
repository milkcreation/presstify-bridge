<?php

namespace tiFy\Contracts\Mail;

interface MailQueue
{
    /**
     * Ajout d'un élément dans la file d'attente
     *
     * @param array $params Paramètre d'expédition du mail.
     * @param string $sending Date de programmation d'envoi du mail au format timestamp.
     * @param array $item_meta Données complémentaires d'envoi du mail.
     *
     * @return int
     */
    public function add($params, $date = 'now', $item_meta = []);
}