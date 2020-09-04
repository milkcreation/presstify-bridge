<?php declare(strict_types=1);

namespace tiFy\Contracts\Mail;

use DateTime;

interface MailerQueue
{
    /**
     * Ajout d'un élément dans la file d'attente
     *
     * @param Mail $mail Instance de l'email.
     * @param string|DateTime $date Date de programmation d'envoi du mail au format timestamp.
     * @param array $params Paramètres complémentaires.
     *
     * @return int
     */
    public function add(Mail $mail, $date = 'now', array $params = []);

    /**
     * Définition du gestionnaire de mails.
     *
     * @param Mailer $manager
     *
     * @return static
     */
    public function setMailer(Mailer $manager): MailerQueue;
}