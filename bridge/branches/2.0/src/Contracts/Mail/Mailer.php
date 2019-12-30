<?php

namespace tiFy\Contracts\Mail;

use tiFy\Contracts\Support\ParamsBag;
use tiFy\Contracts\View\Engine as ViewEngine;

interface Mailer extends ParamsBag
{
    /**
     * Affichage du message en mode déboguage.
     *
     * @param array $params Liste des paramètres de configuration.
     *
     * @return string
     */
    public function debug($params = []);

    /**
     * Récupération du pilote de traitement des e-mails.
     *
     * @return LibraryAdapter
     */
    public function getLib();

    /**
     * Mise en file du message.
     *
     * @param array $params Liste des paramètres de configuration.
     * @param string|\DateTime $date Date de programmation d'expédition du mail. Par defaut, envoi immédiat.
     * @param array $extras Données complémentaires.
     *
     * @return int
     */
    public function queue($params = [], $date = 'now', $extras = []);

    /**
     * Envoi d'un message.
     *
     * @param array $params Liste des paramètres de configuration.
     *
     * @return boolean
     */
    public function send($params = []);

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