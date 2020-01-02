<?php declare(strict_types=1);

namespace tiFy\Partial\Driver\Dropdown;

use tiFy\Contracts\Partial\DropdownItem as DropdownItemContract;
use tiFy\Support\ParamsBag;

class DropdownItem extends ParamsBag implements DropdownItemContract
{
    /**
     * Nom de qualification de l'élément.
     * @var string
     */
    protected $name = '';

    /**
     * CONSTRUCTEUR.
     *
     * @param string $name Nom de qualification de l'élément.
     * @param string|array $attrs
     *
     * @return void
     */
    public function __construct($name, $attrs)
    {
        $this->name = $name;

        if (!is_array($attrs)) {
            $attrs = ['content' => $attrs];
        }

        $this->set($attrs)->parse();
    }

    /**
     * Résolution de sortie du controleur sous la forme d'une chaîne de caractères.
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->getContent();
    }

    /**
     * Récupération du contenu d'affichage de l'élément
     *
     * @return string
     */
    public function getContent()
    {
        return $this->get('content');
    }
}