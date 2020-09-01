<?php declare(strict_types=1);

namespace tiFy\Contracts\Mail;

use DateTime;
use tiFy\Contracts\{Http\Response, Support\ParamsBag};

interface Mail
{
    /**
     * Résolution de sortie de la classe sous la forme d'une chaîne de caractères.
     *
     * @return string
     */
    public function __toString(): string;

    /**
     * Définition des données du message.
     *
     * @param string|array $key
     * @param mixed $value
     *
     * @return static
     */
    public function data($key, $value = null): Mail;

    /**
     * Liste des paramètres par défaut.
     *
     * @return array
     */
    public function defaults(): array;

    /**
     * Récupération de l'affichage du mode de débogage.
     *
     * @return string
     */
    public function debug(): string;

    /**
     * Récupération de l'instance du gestionnaire de mail.
     *
     * @return Mailer|null
     */
    public function mailer(): ?Mailer;

    /**
     * Définition de paramètre|Récupération de paramètres|Récupération de l'instance des paramètres.
     *
     * @param array|string|null $key Liste des définitions de paramètres|Indice de qualification du paramètres à récupérer (Syntaxe à point permise).
     * @param mixed $default Valeur de retour par défaut lors de la récupération de paramètres.
     *
     * @return string|bool|mixed|ParamsBag|null
     */
    public function params($key = null, $default = null);

    /**
     * Mise en file de l'email dans la queue d'expédition.
     *
     * @param DateTime|string $date Date d'expédition.
     * @param array $params Liste des paramètres complémentaires.
     *
     * @return int
     */
    public function queue($date = 'now', array $params = []): int;

    /**
     * Expédition de l'email
     *
     * @return bool
     */
    public function send(): bool;

    /**
     * Définition du gestionnaire de mail
     *
     * @param Mailer $mailer
     *
     * @return static
     */
    public function setMailer(Mailer $mailer): Mail;

    /**
     * Définition de la liste des paramètres.
     *
     * @param array $params
     *
     * @return static
     */
    public function setParams(array $params): Mail;

    /**
     * Affichage de l'email.
     *
     * @return string
     */
    public function render(): string;

    /**
     * Récupération de la reponse HTTP.
     *
     * @return Response
     */
    public function response(): Response;

    /**
     * Récupération de l'affichage d'un gabarit.
     *
     * @param string $name
     * @param array $data
     *
     * @return string
     */
    public function view(string $name, array $data = []): string;
}