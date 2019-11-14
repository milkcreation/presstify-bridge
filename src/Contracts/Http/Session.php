<?php declare(strict_types=1);

namespace tiFy\Contracts\Http;

use Countable;
use IteratorAggregate;
use Psr\Container\ContainerInterface as Container;
use Symfony\Component\HttpFoundation\Session\SessionInterface as BaseSession;

interface Session extends BaseSession, Countable, IteratorAggregate
{
    /**
     * Récupération de l'instance du gestionnaire de session éphémère|ajout d'attributs|récupération d'attributs.
     *
     * @param string|array|null $key
     * @param mixed $value
     *
     * @return SessionFlashBag|$this|mixed|null
     */
    public function flash($key = null, $value = null);

    /**
     * Définition du conteneur d'injection de dépendances.
     *
     * @param Container $container
     *
     * @return static
     */
    public function setContainer(Container $container): Session;

    /**
     * Conservation des données de session éphèmere pour la requête suivante.
     *
     * @param array|null $keys Personnalisation de la liste des clé d'indices à conserver.
     *
     * @return static
     */
    public function reflash(?array $keys = null): Session;
}
