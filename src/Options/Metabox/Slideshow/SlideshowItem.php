<?php

namespace tiFy\Options\Metabox\Slideshow;

use tiFy\Contracts\View\ViewEngine;
use tiFy\Kernel\Params\ParamsBag;

class SlideshowItem extends ParamsBag
{
    /**
     * Indice de l'élément.
     * @var int|string
     */
    protected $index;

    /**
     * Instance du controleur d'affichage de gabarit.
     * @var ViewEngine
     */
    protected $viewer;

    /**
     * CONSTRUCTEUR.
     *
     * @param string $index
     * @param array $attrs Liste des attributs de l'éléments
     * @param ViewEngine $viewer Instance du controleur d'affichage de gabarit.
     *
     * @return void
     */
    public function __construct($index, $attrs, ViewEngine $viewer)
    {
        $this->index = is_null($index) ? uniqid() : $index;
        $this->viewer = $viewer;

        parent::__construct($attrs);
    }

    /**
     * Résolution de sortie de la classe sous forme d'une chaîne de caractères.
     *
     * @return string
     */
    public function __toString()
    {
        return (string)$this->render();
    }

    /**
     * {@inheritdoc}
     */
    public function defaults()
    {
        return [
            'post_id'       => 0,
            'attachment_id' => 0,
            'clickable'     => 0,
            'planning'      => [
                'from'  => 0,
                'start' => '',
                'to'    => 0,
                'end'   => '',
            ]
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function parse($attrs = [])
    {
        parent::parse($attrs);

        $this->set('name', "{$this->get('name')}[items][{$this->index}]");
    }

    /**
     * Récupération du rendu de l'affichage.
     *
     * @return string
     */
    public function render()
    {
        return $this->viewer->make('item', $this->all());
    }
}