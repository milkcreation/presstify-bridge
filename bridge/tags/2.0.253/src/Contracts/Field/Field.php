<?php declare(strict_types=1);

namespace tiFy\Contracts\Field;

use InvalidArgumentException;
use tiFy\Contracts\Support\Manager;

interface Field extends Manager
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
     * @return FieldDriver|null
     *
     * @throws InvalidArgumentException
     */
    public function get(...$args): ?FieldDriver;

    /**
     * Déclaration des instance de portions d'affichage par défaut.
     *
     * @return static
     */
    public function registerDefaults(): Field;

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