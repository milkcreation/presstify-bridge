<?php declare(strict_types=1);

namespace tiFy\Contracts\Template;

interface FactoryActions extends FactoryAwareTrait
{
    /**
     * Exécution de l'action.
     *
     * @param string $name Nom de qualification de l'action.
     * @param array ...$parameters Liste des paramètres dynamiques passés à l'action.
     *
     * @return mixed
     */
    public function execute(string $name, ...$parameters);
}