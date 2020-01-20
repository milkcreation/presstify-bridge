<?php declare(strict_types=1);

namespace tiFy\Contracts\Log;

use Monolog\{Logger as MonologLogger, ResettableInterface};
use Psr\Container\ContainerInterface as Container;
use Psr\Log\LoggerInterface as PsrLoggerInterface;
use tiFy\Contracts\Support\ParamsBag;

/**
 * @mixin MonologLogger
 */
interface Logger extends PsrLoggerInterface, ResettableInterface
{
    /**
     * Alias de création d'un message de notification.
     *
     * @param string $message Intitulé du message.
     * @param array $context Liste des données de contexte.
     *
     * @return boolean
     */
    public function addSuccess(string $message, array $context = []): bool;

    /**
     * Récupération du conteneur d'injection de dépendances.
     *
     * @return Container
     */
    public function getContainer(): ?Container;

    /**
     * Récupération de paramètre|Définition de paramètres|Instance du gestionnaire de paramètre.
     *
     * @param string|array|null $key Clé d'indice du paramètre à récupérer|Liste des paramètre à définir.
     * @param mixed $default Valeur de retour par défaut lorsque la clé d'indice est une chaine de caractère.
     *
     * @return mixed|ParamsBag
     */
    public function params($key = null, $default = null);

    /**
     * Définition du conteneur d'injection de dépendances.
     *
     * @param Container $container
     *
     * @return static
     */
    public function setContainer(Container $container): Logger;

    /**
     * Définition de la liste des attributs de configuration.
     *
     * @param array $params Liste des attributs de configuration.
     *
     * @return static
     */
    public function setParams(array $params): Logger;

    /**
     * Alias de création d'un message de notification.
     *
     * @param string $message Intitulé du message.
     * @param array $context Liste des données de contexte.
     *
     * @return boolean
     */
    public function success(string $message, array $context = []): bool;
}