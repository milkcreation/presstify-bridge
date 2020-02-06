<?php declare(strict_types=1);

namespace tiFy\Contracts\View;

use LogicException;
use Psr\Container\ContainerInterface as Container;

/**
 * @mixin \tiFy\View\Engine\Engine
 */
interface View
{
    /**
     * Définition du répertoire par défaut des gabarits d'affichage.
     *
     * @param string $dir Chemin absolu vers le répertoire.
     *
     * @return void
     */
    public static function setDefaultDirectory(string $dir): void;

    /**
     * Délégation d'appel des méthodes du moteur de templates.
     *
     * @param string $method Nom de qualification de la méthode
     * @param array $parameters Liste des paramètres passés en argument à la méthode.
     *
     * @return mixed
     */
    public function __call(string $method, array $parameters);

    /**
     * Récupération de l'instance du conteneur d'injection de dépendances.
     *
     * @return Container|null
     */
    public function getContainer(): ?Container;

    /**
     * Récupération du chemin absolu vers le repertoire par défaut des templates.
     *
     * @return string
     */
    public function getDefaultDirectory(): string;

    /**
     * Récupération d'une instance du moteur de templates par défaut.
     *
     * @return Engine
     */
    public function getDefaultEngine(): Engine;

    /**
     * Récupération d'un moteur de templates déclaré.
     *
     * @param string|null $name Nom de qualification du moteur.
     *
     * @return Engine|null
     */
    public function getEngine(?string $name = null): ?Engine;

    /**
     * Récupération d'une instance de moteur de templates basé sur plates.
     *
     * @param array $params Liste des paramètres de configuration.
     *
     * @return PlatesEngine
     */
    public function getPlatesEngine(array $params = []): PlatesEngine;

    /**
     * Déclaration d'un moteur de templates.
     *
     * @param string $name Nom de qualification du moteur.
     * @param string|array|Engine|null $attrs Alias du conteneur|Attribut de configuration|Instance du moteur de
     * templates|Instance du moteur de template par défaut.
     *
     * @return Engine
     *
     * @throws LogicException
     */
    public function register(string $name, $attrs = null): Engine;

    /**
     * Définition d'un moteur de templates.
     *
     * @param string $name Nom de qualification du moteur.
     * @param Engine $engine Instance du moteur de templates.
     *
     * @return static
     */
    public function setEngine(string $name, Engine $engine): View;
}