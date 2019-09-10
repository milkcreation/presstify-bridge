<?php declare(strict_types=1);

namespace tiFy\Contracts\Partial;

use InvalidArgumentException;
use tiFy\Contracts\Support\Manager;

interface Partial extends Manager
{
    /**
     * {@inheritDoc}
     *
     * @param string $Alias Alias de qualification.
     * @param string|array|null $id Identifiant de qualification d'un élément spécifique ou Liste des attributs de
     *                              configuration personnalisés.
     * @param array $attrs Liste des attributs de configuration personnalisés lorsque l'identifiant de qualification
     *                     est défini.
     *
     * @return PartialFactory|null
     *
     * @throws InvalidArgumentException
     */
    public function get(...$args): ?PartialFactory;

    /**
     * Déclaration des instance de portions d'affichage par défaut.
     *
     * @return static
     */
    public function registerDefaults(): Partial;

    /**
     * Récupération du chemin absolu vers le répertoire des ressources.
     *
     * @param string $path Chemin relatif du sous-repertoire.
     *
     * @return string
     */
    public function resourcesDir(?string $path = null): string;

    /**
     * Récupération de l'url absolue vers le répertoire des ressources.
     *
     * @param string $path Chemin relatif du sous-repertoire.
     *
     * @return string
     */
    public function resourcesUrl(?string $path = null): string;
}