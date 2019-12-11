<?php declare(strict_types=1);

namespace tiFy\Partial\Partials\Pdfviewer;

use tiFy\Contracts\Partial\{PartialFactory as PartialFactoryContract, Pdfviewer as PdfviewerContract};
use tiFy\Partial\PartialFactory;

class Pdfviewer extends PartialFactory implements PdfviewerContract
{
    /**
     * @inheritDoc
     *
     * @return array {
     * @var array $attrs Attributs HTML du champ.
     * @var string $after Contenu placé après le champ.
     * @var string $before Contenu placé avant le champ.
     * @var array $viewer Liste des attributs de configuration du pilote d'affichage.
     * }
     */
    public function defaults(): array
    {
        return [
            'attrs'   => [],
            'after'   => '',
            'before'  => '',
            'viewer'  => [],
            'defer'   => false,
            'content' => [
                'footer' => false,
                'header' => false,
            ],
            'nav'     => [
                'first'  => '&laquo;',
                'prev'   => '&lsaquo;',
                'next'   => '&rsaquo;',
                'last'   => '&raquo;',
                'status' => true,
            ],
            'spinner' => true,
            'src'     => 'https://raw.githubusercontent.com/mozilla/pdf.js/ba2edeae/examples/learning/helloworld.pdf',
        ];
    }

    /**
     * @inheritDoc
     */
    public function parse(): PartialFactoryContract
    {
        parent::parse();

        $defaultClasses = [
            'body'    => 'Pdfviewer-contentBody',
            'canvas'  => 'Pdfviewer-canvas',
            'content' => 'Pdfviewer-content',
            'current' => 'Pdfviewer-navCurrent',
            'first'   => 'Pdfviewer-navLink Pdfviewer-navLink--first',
            'footer'  => 'Pdfviewer-contentFooter',
            'header'  => 'Pdfviewer-contentHeader',
            'last'    => 'Pdfviewer-navLink Pdfviewer-navLink--last',
            'nav'     => 'Pdfviewer-nav',
            'next'    => 'Pdfviewer-navLink Pdfviewer-navLink--next',
            'prev'    => 'Pdfviewer-navLink Pdfviewer-navLink--prev',
            'status'  => 'Pdfviewer-navStatus',
            'total'   => 'Pdfviewer-navTotal',
            'spinner' => 'Pdfviewer-spinner',
        ];
        foreach ($defaultClasses as $k => $v) {
            $this->set(["classes.{$k}" => sprintf($this->get("classes.{$k}", '%s'), $v)]);
        }

        $this->set([
            'attrs.data-control' => 'pdfviewer',
            'attrs.data-options' => [
                'classes' => $this->get('classes'),
                'content' => $this->get('content'),
                'defer'   => !!$this->get('defer'),
                'spinner' => !!$this->get('spinner'),
                'nav'     => $this->get('nav'),
                'src'     => $this->get('src'),
            ],
        ]);

        return $this;
    }
}