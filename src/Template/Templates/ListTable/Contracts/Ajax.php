<?php declare(strict_types=1);

namespace tiFy\Template\Templates\ListTable\Contracts;

use tiFy\Contracts\Support\ParamsBag;
use tiFy\Contracts\Template\FactoryAwareTrait;

interface Ajax extends FactoryAwareTrait, ParamsBag
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