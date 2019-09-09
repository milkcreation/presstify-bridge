<?php

namespace tiFy\Kernel\Walker;

use Illuminate\Support\Arr;
use tiFy\Kernel\Tools;

class WalkerBaseController
{
    /**
     * Classe de récupération de la liste des éléments à afficher
     * @var WalkerItemCollectionBaseController
     */
    protected $itemCollection;

    /**
     * Liste des options.
     * @var array {
     *
     *      @var string $indent Caractère d'indendation.
     *      @var int $start_indent Nombre de caractère d'indendation au départ.
     *      @var bool|string $sort Ordonnancement des éléments.false|true|append(défaut)|prepend.
     *      @var string $prefixe Préfixe de nommage des éléments HTML.
     *      @var string $items_controller Controleur de traitement de la liste des éléments.
     *      @var string $item_controller Controleur de traitement d'un élément.
     * }
     */
    protected $options = [
        'indent'           => "\t",
        'start_indent'     => 0,
        'sort'             => 'append',
        'prefix'           => 'tiFyWalker-',
        'items_controller' => '',
        'item_controller'  => '',
    ];

    /**
     * CONSTRUCTEUR.
     *
     * @param array $items Liste de éléments à traiter.
     * @parma array $options Liste des options de traitement.
     *
     * @return void
     */
    public function __construct($items = [], $options = [])
    {
        $items_controller = $this->getOption('items_controller') ? : WalkerItemCollectionBaseController::class;

        $this->itemCollection = new $items_controller($items, $this);

        $this->options = array_merge($this->options, $options);
    }

    /**
     * Fermeture d'un élement.
     *
     * @param WalkerItemBaseController $item Élément courant.
     *
     * @return string
     */
    final protected function _closeItem($item)
    {
        return method_exists($this, 'closeItem_' . $item->getName())
            ? call_user_func([$this, 'closeItem_' . $item->getName()], $item)
            : call_user_func([$this, 'closeItem'], $item);
    }

    /**
     * Fermeture d'une liste d'éléments
     *
     * @param WalkerItemBaseController $item Élément courant.
     *
     * @return string
     */
    final protected function _closeitems($item)
    {
        return method_exists($this, 'closeItems_' . $item->getName())
            ? call_user_func([$this, 'closeItems_' . $item->getName()], $item)
            : call_user_func([$this, 'closeItems'], $item);
    }

    /**
     * Rendu d'un élément
     *
     * @param WalkerItemBaseController $item Élément courant.
     *
     * @return string
     */
    final protected function _contentItem($item)
    {
        return method_exists($this, 'contentItem_' . $item->getName())
            ? call_user_func([$this, 'contentItem_' . $item->getName()], $item)
            : call_user_func([$this, 'contentItem'], $item);
    }

    /**
     * Ouverture d'un élement.
     *
     * @param WalkerItemBaseController $item Élément courant.
     *
     * @return string
     */
    final protected function _openItem($item)
    {
        return method_exists($this, 'openItem_' . $item->getName())
            ? call_user_func([$this, 'openItem_' . $item->getName()], $item)
            : call_user_func([$this, 'openItem'], $item);
    }

    /**
     * Ouverture d'une liste d'éléments.
     *
     * @param WalkerItemBaseController $item Élément courant.
     *
     * @return string
     */
    final protected function _openItems($item)
    {
        return method_exists($this, 'openItems_' . $item->getName())
            ? call_user_func([$this, 'openItems_' . $item->getName()], $item)
            : call_user_func([$this, 'openItems'], $item);
    }

    /**
     * Fermeture par défaut d'un contenu d'élement
     *
     * @param WalkerItemBaseController $item Élément courant.
     *
     * @return string
     */
    public function closeItem($item)
    {
        return $this->getIndent($item->getDepth()) . "</div>\n";
    }

    /**
     * Fermeture d'une liste d'éléments.
     *
     * @param WalkerItemBaseController $item Élément courant.
     *
     * @return string
     */
    public function closeItems($item)
    {
        return $this->getIndent($item->getDepth()) . "</div>\n";
    }

    /**
     * Rendu par défaut d'un contenu d'élément
     *
     * @param WalkerItemBaseController $item Élément courant.
     *
     * @return string
     */
    public function contentItem($item)
    {
        return is_callable($item->getContent())
            ? call_user_func_array($item->getContent(), $item->getArgs())
            : $item->getContent();
    }

    /**
     * Récupération static de l'affichage.
     *
     * @param array $items Liste de éléments à traiter.
     * @parma array $options Liste des options de traitement.
     *
     * @return string
     */
    public static function display($items = [], $options = [])
    {
        $self = new static($items, $options);

        return (string)$self;
    }

    /**
     * Récupération de l'indentation d'un élément.
     *
     * @param int $depth Niveau de profondeur de l'élément.
     *
     * @return string
     */
    public function getIndent($depth = 0)
    {
        return str_repeat($this->getOption('indent'), $depth + $this->getOption('start_indent'));
    }

    /**
     * Récupération d'une option.
     *
     * @param string $key Clé d'indexe de l'option.
     * @param mixed $default Valeur de retour par défaut.
     *
     * @return mixed
     */
    public function getOption($key, $default = null)
    {
        return Arr::get($this->options, $key, $default);
    }

    /**
     * Récupération de la liste des options.
     *
     * @return array
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * Ouverture d'une liste d'éléments.
     *
     * @param WalkerItemBaseController $item Élément courant.
     *
     * @return string
     */
    public function openItem($item)
    {
        return $this->getIndent($item->getDepth()) . "<div " . $item->getHtmlAttrs() . ">\n";
    }

    /**
     * Ouverture par défaut d'une liste de contenus d'éléments
     *
     * @param WalkerItemBaseController $item Élément courant.
     *
     * @return string
     */
    public function openItems($item)
    {
        return $this->getIndent($item->getDepth()) . "<div class=\"{$this->getOption('prefix')}-Items {$this->getOption('prefix')}-Items--{$item->getDepth()}\">\n";
    }

    /**
     * Ordonnancement des éléments.
     *
     * @param WalkerItemCollectionBaseController $items Liste des éléments à ordonner.
     *
     * @return array
     */
    public function sort($items = [])
    {
        return $items;

        if (!$sort = $this->getOption('sort', false)) :
            return $items;
        endif;

        $positions = [];
        /** @var WalkerItemBaseController $item */
        foreach ($items as $k => $item) :
            if (! $item->getParent()) :
                continue;
            endif;

            $positions[$k] = $item->getPosition() ? : null;
        endforeach;

        // Bypass - Aucun élément à traiter
        if (empty($positions)) :
            return [];
        endif;

        // Récupération des informations de position
        $max = max($positions);
        $min = ($positions);
        $count = count($positions);
        $i = 1;
        $sorted = [];

        foreach ($positions as $k => $position) :
            if (is_null($position)) :
                switch ($sort) :
                    default :
                    case 'append' :
                        $position = $max++;
                        break;
                    case 'prepend' :
                        $position = $min++ - $count;
                        break;
                endswitch;
            endif;

            if (isset($sorted[$position])) :
                switch ($sort) :
                    default :
                    case 'append' :
                        $position = (float)$position . "." . $i++;
                        break;
                    case 'prepend' :
                        $position = (float)($position - 1) . "." . (99999 - ($count + $i++));
                        break;
                endswitch;
            endif;

            $sorted[$position] = $items[$k];
        endforeach;

        ksort($sorted);

        return $sorted;
    }

    /**
     * Itérateur d'affichage.
     *
     * @param WalkerItemCollectionBaseController $items Liste des éléments à ordonner.
     * @param int $depth Niveau de profondeur courant.
     * @param string $parent Nom de qualification de l'élément parent.
     *
     * @return string
     */
    public function walk($items = [], $depth = 0, $parent = '')
    {
        $output = "";

        $sorted = $this->sort($items);

        $open = false;
        /** @var WalkerItemBaseController $item */
        foreach ($sorted as $item) :
            if ($item->getParent() != $parent) :
                continue;
            endif;

            $item->setDepth($depth);

            if (!$open) :
                $open = $item;
                $output .= $this->_openItems($open);
            endif;

            $output .= $this->_openItem($item);
            $output .= $this->_contentItem($item);
            $output .= $this->walk($items, ($depth + 1), $item->getName());
            $output .= $this->_closeItem($item);

            $close = $item;
        endforeach;

        if ($open) :
            $output .= $this->_closeItems($close);
        endif;

        return $output;
    }

    /**
     * Résolution de sortie de la classe en tant que chaîne de caractère.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->walk($this->itemCollection);
    }
}