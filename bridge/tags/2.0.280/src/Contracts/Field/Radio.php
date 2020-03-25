<?php declare(strict_types=1);

namespace tiFy\Contracts\Field;

interface Radio extends FieldDriver
{
    /**
     * Vérification de correspondance entre la valeur de coche et celle du champ.
     *
     * @return bool
     */
    public function isChecked(): bool;
}