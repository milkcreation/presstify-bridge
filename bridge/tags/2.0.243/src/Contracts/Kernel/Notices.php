<?php declare(strict_types=1);

namespace tiFy\Contracts\Kernel;

use tiFy\Contracts\View\Engine as ViewEngine;

interface Notices
{
    /**
     * Ajout d'un message de notification.
     *
     * @param string $type Type de notification.
     * @param string $message Message de notification.
     * @param array $datas Liste des données embarquées associées.
     *
     * @return string Identifiant de qualification de la notification.
     */
    public function add($type, $message = '', $datas = []);

    /**
     * Récupération de la liste des notifications.
     *
     * @return array
     */
    public function all();

    /**
     * Suppression de la liste des notifications.
     *
     * @param string $type Type de notification.
     *
     * @return void
     */
    public function clear($type = null);

    /**
     * Compte le nombre de notifications par type.
     *
     * @param string $type Type de notification.
     *
     * @return int
     */
    public function count($type);

    /**
     * Vérification d'existance de message de notification.
     *
     * @return boolean
     */
    public function exists(): bool;

    /**
     * Récupération de la liste d'une notification associée à un type.
     *
     * @param string $type Type de notification.
     *
     * @return array
     */
    public function get($type);

    /**
     * Vérification d'existance d'une notification associée un type.
     *
     * @param string $type Type de notification.
     *
     * @return bool
     */
    public function has($type);

    /**
     * Récupération des données associées.
     *
     * @param null|string $type Type de notification.
     *
     * @return array
     */
    public function getDatas($type = null);

    /**
     * Récupération des messages de notification.
     *
     * @param null|string $type Type de notification.
     *
     * @return array
     */
    public function getMessages($type = null);

    /**
     * Récupération de la liste des types de notification déclarés.
     *
     * @return string[]
     */
    public function getTypes();

    /**
     * Vérification d'existance d'un type de notification.
     *
     * @param string $type Type de notification.
     *
     * @return bool
     */
    public function hasType($type);

    /**
     * Récupération de notification selon une liste d'arguments.
     *
     * @param string $type Type de notification.
     * @param array $query_args Liste d'arguments de données.
     *
     * @return array
     */
    public function query($type, $query_args = []);

    /**
     * Affichage des messages de notification par type.
     *
     * @param string $type Type de notification.
     *
     * @return string
     */
    public function render($type);

    /**
     * Ajout d'un type de notification permis.
     *
     * @param string $type Type de notification permis.
     *
     * @return void
     */
    public function setType($type);

    /**
     * Définition des types de notification.
     *
     * @param array $types Type de notification permis.
     *
     * @return void
     */
    public function setTypes($types = ['error', 'warning', 'info', 'success']);

    /**
     * Récupération d'un instance du controleur de liste des gabarits d'affichage ou d'un gabarit d'affichage.
     * {@internal Si aucun argument n'est passé à la méthode, retourne l'instance du controleur de liste.}
     * {@internal Sinon récupére l'instance du gabarit d'affichage et passe les variables en argument.}
     *
     * @param null|string view Nom de qualification du gabarit (optionnel).
     * @param array $data Liste des variables passées en argument.
     *
     * @return ViewEngine|string
     */
    public function viewer(string $view = null, array $data = []);
}