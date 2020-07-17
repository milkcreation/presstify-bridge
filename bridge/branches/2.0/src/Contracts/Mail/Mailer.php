<?php declare(strict_types=1);

namespace tiFy\Contracts\Mail;

use DateTime;
use Psr\Container\ContainerInterface;
use tiFy\Contracts\Container\Container;

interface Mailer
{
    /**
     * Récupération de la liste des paramètres globaux par défaut des mails.
     *
     * @return
     */
    public static function getDefaults(): array;

    /**
     * Définition de la liste des paramètres globaux par défaut des mails.
     *
     * @param array $attrs
     *
     * @return void
     */
    public static function setDefaults(array $attrs = []): void;

    /**
     * Mise en file d'un email dans la queue d'expedition de mail.
     *
     * @param Mail $mail Instance de l'email.
     * @param DateTime|string $date Date d'expédition.
     * @param array $params Liste des paramètres d'expédition complémentaire.
     *
     * @return int
     */
    public function addQueue(Mail $mail, $date = 'now', array $params = []): int;

    /**
     * Réinitialisation du pilote d'expédition des emails.
     *
     * @return static
     */
    public function clearDriver(): Mailer;

    /**
     * Création de l'email.
     *
     * @param Mail|array|null $attrs Instance de l'email|Liste des paramètres de configuration|Email courant si null.
     *
     * @return Mail
     */
    public function create($attrs = null): Mail;

    /**
     * Affichage du message en mode déboguage.
     *
     * @param Mail|array|null $attrs Instance de l'email|Liste des paramètres de configuration|Email courant si null.
     *
     * @return void
     */
    public function debug($attrs = null): void;

    /**
     * Récupération du conteneur d'injection de dépendance.
     *
     * @return Container|ContainerInterface|null
     */
    public function getContainer(): ?ContainerInterface;

    /**
     * Récupération du pilote de traitement des e-mails.
     *
     * @return MailerDriver
     */
    public function getDriver(): MailerDriver;

    /**
     * Récupération de l'instance de gestionnaire de file de mails.
     *
     * @return MailerQueue
     *
     * @todo
     */
    public function getQueue(): MailerQueue;

    /**
     * Préparation du mail pour l'expédition.
     *
     * @return static
     */
    public function prepare(): Mailer;

    /**
     * Mise en file du message.
     *
     * @param Mail|array|null $attrs Instance de l'email|Liste des paramètres de configuration|Email courant si null.
     * @param string|DateTime $date Date de programmation d'expédition du mail. Par defaut, envoi immédiat.
     * @param array $extras Données complémentaires.
     *
     * @return int
     *
     * @todo
     */
    public function queue($attrs = null, $date = 'now', array $extras = []): int;

    /**
     * Envoi d'un message.
     *
     * @param Mail|array|null $attrs Instance de l'email|Liste des paramètres de configuration|Email courant si null.
     *
     * @return boolean
     */
    public function send($attrs = null): bool;

    /**
     * Définition du conteneur d'injection de dépendance.
     *
     * @param ContainerInterface $container
     *
     * @return static
     */
    public function setContainer(ContainerInterface $container): Mailer;
}