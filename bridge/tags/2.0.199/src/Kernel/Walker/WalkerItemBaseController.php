<?php

namespace tiFy\Kernel\Walker;

use tiFy\Kernel\Params\ParamsBag;
use tiFy\Support\HtmlAttrs;

class WalkerItemBaseController extends ParamsBag
{
    /**
     * Classe de rappel du controleur principal
     * @var WalkerBaseController
     */
    protected $walker;

    /**
     * Liste des attributs de configuration de l'élément.
     * @var array {
     *      @var string $name Nom de qualification de l'élément.
     *      @var string $parent Nom de qualification de l'élément parent.
     *      @var string|callable Intitulé de l'élément.
     *      @var string|callable Contenu de l'élément.
     *      @var array $attrs Attributs HTML de la balise du conteneur de l'élément.
     *      @var int $position Ordre d'affichage de l'élément dans le parent associé.
     *      @var bool $current Définit l'élément en tant qu'élément courant
     * }
     */
    protected $attributes = [
        'parent'   => '',
        'content'  => '',
        'title'    => '',
        'attrs'    => [],
        'position' => 0,
        'current'  => false
    ];

    /**
     * Nom de qualification de l'élément.
     * @var string
     */
    protected $name = '';

    /**
     * Niveau de profondeur d'affichage de l'élément.
     * @var int
     */
    protected $depth = 0;

    /**
     * CONSTRUCTEUR.
     *
     * @param string $name Nom de qualification de l'élément.
     * @param array $attrs Liste des attributs de configuration.
     *
     * @return void
     */
    public function __construct($name, $attrs = [], $walker)
    {
        $this->walker = $walker;
        $this->name = $name;

        $this->parse($attrs);

        parent::__construct($this->attributes);
    }

    /**
     * Récupération de la liste des variables passées en arguments lorsque le contenu est de type callable.
     *
     * @return string
     */
    public function getArgs()
    {
        return $this->get('args', []);
    }

    /**
     * Récupération du contenu de l'élément.
     *
     * @return string|callable
     */
    public function getContent()
    {
        return $this->get('content', '');
    }

    /**
     * Récupération du niveau de profondeur d'affichage de l'élément.
     *
     * @return int
     */
    public function getDepth()
    {
        return $this->depth;
    }

    /**
     * Récupération du nom de qualification de l'élément.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Récupération du nom de qualification du parent de l'élément.
     *
     * @return string
     */
    public function getParent()
    {
        return $this->get('parent', '');
    }

    /**
     * Récupération de l'intitulé de l'élément.
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->get('title', '');
    }

    /**
     * Récupération de l'ordre d'affichage de l'élément dans l'élément parent.
     *
     * @return string
     */
    public function getPosition()
    {
        return $this->get('position', '');
    }

    /**
     * Récupération de la liste des attributs de balise HTML.
     *
     * @param array $item Élément courant.
     *
     * @return string
     */
    public function getHtmlAttrs()
    {
        $this->parseHtmlAttrs();

        return HtmlAttrs::createFromAttrs($this->get('attrs', []));
    }

    /**
     * Traitement de la liste des attributs de balise HTML.
     *
     * @return array
     */
    public function parseHtmlAttrs()
    {
        if(empty($this->get('attrs.id', ''))) :
            $this->set('attrs.id', "{$this->walker->getOption('prefix')}Item--{$this->getName()}");
        endif;

        if (empty($this->get('attrs.class', ''))) :
            $this->set('attrs.class', "{$this->walker->getOption('prefix')}Item {$this->walker->getOption('prefix')}Item--{$this->getName()}");
        endif;

        $this->set('attrs.aria-current', $this->get('current', false) ? 'true' : 'false');
    }

    /**
     * Définition du niveau de profondeur d'affichage de l'élément.
     *
     * @return void
     */
    public function setDepth($depth)
    {
        $this->depth = $depth;
    }
}