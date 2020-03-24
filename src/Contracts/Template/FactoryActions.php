<?php declare(strict_types=1);

namespace tiFy\Contracts\Template;

use Exception;

interface FactoryActions extends FactoryAwareTrait
{
    /**
     * Récupération de la clé d'indice de déclénchment d'une action.
     *
     * @return string
     */
    public function getIndex(): string;

    /**
     * Exécution de l'action.
     *
     * @param string $name Nom de qualification de l'action.
     * @param array ...$parameters Liste des paramètres dynamiques passés à l'action.
     *
     * @return mixed
     *
     * @throws Exception
     */
    public function execute(string $name, ...$parameters);
}