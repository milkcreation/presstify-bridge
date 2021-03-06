<?php declare(strict_types=1);

namespace tiFy\Template\Templates\ListTable\Contracts;

use tiFy\Contracts\Template\{FactoryAjax, FactoryAwareTrait};

interface Ajax extends FactoryAwareTrait, FactoryAjax
{
    /**
     * Récupération de la liste des colonnes.
     *
     * @return array
     */
    public function getColumns(): array;

    /**
     * Récupération de la liste des translations.
     *
     * @return array
     */
    public function getLanguage(): array;

    /**
     * Traitement de la liste des options.
     *
     * @param array $options
     *
     * @return array
     */
    public function parseOptions(array $options = []): array;
}