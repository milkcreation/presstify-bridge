<?php declare(strict_types=1);

namespace tiFy\Contracts\Validation;

use Psr\Container\ContainerInterface as Container;
use Respect\Validation\Validatable;

interface Rule extends Validatable
{
    /**
     * Récupération de l'instance du conteneur d'injection de dépendance.
     *
     * @return Container|null
     */
    public function getContainer(): ?Container;

    /**
     * Définition de la liste des arguments.
     *
     * @param array ...$args Liste dynamique des arguments.
     *
     * @return static
     */
    public function setArgs(...$args): Rule;

    /**
     * Définition de l'instance du gestionnaire de validation.
     *
     * @param Validator $validator
     *
     * @return static
     */
    public function setValidator(Validator $validator): Rule;

    /**
     * Test de validation.
     *
     * @param string $input
     *
     * @return bool
     */
    public function validate($input);
}