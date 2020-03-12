<?php declare(strict_types=1);

namespace tiFy\Form\Exception;

use tiFy\Contracts\Form\FactoryField;

class FieldException extends FormException
{
    /**
     * Instance du champ associé.
     * @var FactoryField|null
     */
    protected $field;

    /**
     * Identifiant de qualification du champ associé.
     * @var string|null
     */
    protected $slug;

    /**
     * Récupération de l'instance du champ associé.
     *
     * @return FactoryField
     */
    public function getField(): ?FactoryField
    {
        return $this->field;
    }

    /**
     * Récupération de l'identifiant de qualification du champ associé.
     *
     * @return string
     */
    public function getSlug(): string
    {
        return ($field = $this->getField()) ? $field->getSlug() : $this->slug;
    }

    /**
     * Définition de l'instance du champ associé.
     *
     * @param FactoryField $field
     *
     * @return static
     */
    public function setField(FactoryField $field): self
    {
        $this->field = $field;

        return $this;
    }

    /**
     * Définition de l'identifiant de qualification du champ associé.
     *
     * @param string $slug
     *
     * @return static
     */
    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }
}