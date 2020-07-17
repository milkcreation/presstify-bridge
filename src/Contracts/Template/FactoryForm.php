<?php declare(strict_types=1);

namespace tiFy\Contracts\Template;

use tiFy\Contracts\Support\ParamsBag;

interface FactoryForm extends FactoryAwareTrait, ParamsBag
{
    /**
     * Récupération de champs cachés associés au formulaire.
     *
     * @param string|null $key
     * @param string $default
     *
     * @return string|array
     */
    public function getHidden(?string $key = null, string $default = '');

    /**
     * Définition de champs cachés associés au formulaire.
     *
     * @param string|array $key
     * @param string $value
     *
     * @return static
     */
    public function setHidden($key, string $value = ''): FactoryForm;
}