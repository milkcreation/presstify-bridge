<?php declare(strict_types=1);

namespace tiFy\Contracts\Mail;

use DateTime;
use Psr\Container\ContainerInterface as Container;
use tiFy\Contracts\{View\Engine as ViewEngine};

interface Mailer
{
    /**
     * Définition de la liste des arguments utilisés par défaut.
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
     * @return Container|null
     */
    public function getContainer(): ?Container;

    /**
     * Récupération du pilote de traitement des e-mails.
     *
     * @return MailerDriver
     */
    public function getDriver(): MailerDriver;

    /**
     * Récupération de l'instance de gestionnaire de file de mails.
     *
     * @return MailerDriver
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
     * @param Container $container
     *
     * @return static
     */
    public function setContainer(Container $container): Mailer;

    /**
     * Récupération d'un instance du controleur de liste des gabarits d'affichage ou d'un gabarit d'affichage.
     * {@internal Si aucun argument n'est passé à la méthode, retourne l'instance du controleur de liste.}
     * {@internal Sinon récupére l'instance du gabarit d'affichage et passe les variables en argument.}
     *
     * @param null|string view Nom de qualification du gabarit.
     * @param array $data Liste des variables passées en argument.
     *
     * @return ViewEngine|string
     */
    public function viewer(?string $view = null, array $data = []);
}