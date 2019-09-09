<?php

namespace tiFy\Kernel\Walker;

abstract class AbstractWalkerMenuTree extends WalkerBaseController
{
    /**
     * Liste des options.
     * @var array {
     *
     *      @var string $indent Caractère d'indendation.
     *      @var int $start_indent Nombre de caractère d'indendation au départ.
     *      @var bool|string $sort Ordonnancement des éléments.false|true|append(défaut)|prepend.
     *      @var string $prefixe Préfixe de nommage des éléments HTML.
     * }
     */
    protected $options = [
        'indent'       => "\t",
        'start_indent' => 0,
        'sort'         => 'append',
        'prefix'       => 'tiFyWalkerMenuTree-'
    ];

    /**
     * {@inheritdoc}
     */
    public function closeItems($item)
    {
        return $this->getIndent($item->getDepth()) . "\t\t</ul>\n";
    }

    /**
     * {@inheritdoc}
     */
    public function closeItem($item)
    {
        return $this->getIndent($item->getDepth()) . "\t</li>\n";
    }

    /**
     * {@inheritdoc}
     */
    public function openItem($item)
    {
        return $this->getIndent($item->getDepth()) . "\t<li ". $item->getHtmlAttrs() .">\n";
    }

    /**
     * {@inheritdoc}
     */
    public function openItems($item)
    {
        return $this->getIndent($item->getDepth()) . "\t\t<ul class=\"{$this->getOption('prefix')}Items {$this->getOption('prefix')}Items--{$item->getDepth()}\">\n";
    }
}