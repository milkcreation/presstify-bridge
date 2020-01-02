<?php declare(strict_types=1);

namespace tiFy\Partial\Driver\Pagination;

use tiFy\Contracts\Partial\{Pagination as PaginationContract, PartialDriver as PartialDriverContract};
use tiFy\Partial\PartialDriver;

class Pagination extends PartialDriver implements PaginationContract
{
    /**
     * @var PaginationQuery
     */
    protected $query;

    /**
     * @var PaginationUrl
     */
    protected $url;

    /**
     * {@inheritDoc}
     *
     * @return array {
     *      @var array $attrs Attributs HTML du champ.
     *      @var string $after Contenu placé après le champ.
     *      @var string $before Contenu placé avant le champ.
     *      @var array $viewer Liste des attributs de configuration du pilote d'affichage.
     *      @var array $links {
     *          @var boolean|array $first Activation du lien vers la première page|Liste d'attributs.
     *          @var boolean|array $last Activation du lien vers la dernière page|Liste d'attributs.
     *          @var boolean|array $previous Activation du lien vers la page précédente|Liste d'attributs.
     *          @var boolean|array $next Activation du lien vers la page suivante|Liste d'attributs.
     *          @var boolean|array $numbers Activation de l'affichage de la numérotation des pages|Liste d'attributs {
     *              @var int $range
     *              @var int $anchor
     *              @var int $gap
     *          }
     *      }
     *      @var array|PaginationQuery|object $query Arguments de requête|Instance du controleur de traitement
     *                                               des requêtes.
     *      @var PaginationUrl|string $url Url de lien vers les pages. %d correspond au numéro de page.
     * }
     */
    public function defaults(): array
    {
        return [
            'attrs'         => [],
            'after'         => '',
            'before'        => '',
            'viewer'        => [],
            'links'  => [
                'first'    => true,
                'last'     => true,
                'previous' => true,
                'next'     => true,
                'numbers'  => true,
            ],
            'query'  => [],
            'url'    => '',
        ];
    }

    /**
     * Récupération d'un séparateur de nombre.
     *
     * @param array $numbers Liste des numéros de page existants.
     *
     * @return void
     */
    public function ellipsis(&$numbers)
    {
        $numbers[] = [
            'tag'     => 'span',
            'content' => '...',
            'attrs'   => 'Pagination-itemEllipsis'
        ];
    }

    /**
     * Boucle de récupération des numéros de page.
     *
     * @param array $numbers Liste des numéros de page existants.
     * @param int $start Démarrage de la boucle de récupération.
     * @param int $end Fin de la boucle de récupération.
     *
     * @return void
     */
    public function numLoop(&$numbers, $start, $end)
    {
        for ($num = $start; $num <= $end; $num++) {
            if ($num == 1 && !$this->query->getPage()) {
                $current = 'true';
            } elseif ($this->query->getPage() == $num) {
                $current = 'true';
            } else {
                $current = 'false';
            }

            $numbers[] = [
                'tag'     => 'a',
                'content' => $num,
                'attrs'   => [
                    'class'        => 'Pagination-itemPage Pagination-itemPage--link',
                    'href'         => $this->url->page($num),
                    'aria-current' => $current
                ]
            ];
        }
    }

    /**
     * @inheritDoc
     */
    public function parse(): PartialDriverContract
    {
        parent::parse();

        $this->url = $this->get('url', []);
        if (!$this->url instanceof PaginationUrl) {
            $this->url = new PaginationUrl($this->url);
        }
        $this->set('url', $this->url);

        $this->query = $this->get('query', []);
        if (!$this->query instanceof PaginationQuery) {
            $this->query = new PaginationQuery($this->query);
        }
        $this->set('query', $this->query);

        $this->parseLinks();

        if ($this->get('links.numbers')) {
            $this->parseNumbers();
        }

        $this->viewer()->setFactory(PaginationView::class);

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function parseLinks()
    {
        $defaults = [
            'first'    => [
                'tag'     => 'a',
                'content' => '&laquo;',
                'attrs'   => [
                    'class' => 'Pagination-itemPage Pagination-itemPage--link',
                    'href'  => $this->url->page(1),
                ]
            ],
            'last'     => [
                'tag'     => 'a',
                'content' => '&raquo;',
                'attrs'   => [
                    'class' => 'Pagination-itemPage Pagination-itemPage--link',
                    'href'  => $this->url->page($this->query->getTotalPage()),
                ]
            ],
            'previous' => [
                'tag'     => 'a',
                'content' => '&lsaquo;',
                'attrs'   => [
                    'class' => 'Pagination-itemPage Pagination-itemPage--link',
                    'href'  => $this->url->page($this->query->getPage() - 1),
                ]
            ],
            'next'     => [
                'tag'     => 'a',
                'content' => '&rsaquo;',
                'attrs'   => [
                    'class' => 'Pagination-itemPage Pagination-itemPage--link',
                    'href'  => $this->url->page($this->query->getPage() + 1),
                ]
            ]
        ];

        foreach ($defaults as $link => $default) {
            $attrs = $this->get("links.{$link}", []);

            if ($attrs === false) {
            } elseif ($attrs === true) {
                $attrs = $default;
            } elseif (is_string($attrs)) {
                $attrs = array_merge($default, ['content' => $attrs]);
            } else {
                $attrs = array_merge($default, $attrs);
            }

            $this->set("links.{$link}", $attrs);
        }
    }

    /**
     * Traitement de la liste des numéros de page.
     *
     * @return void
     */
    public function parseNumbers()
    {
        $range = intval($this->get('links.numbers.range', 2));
        $anchor = intval($this->get('links.numbers.anchor', 3));
        $gap = intval($this->get('links.numbers.gap', 1));

        $min_links = ($range * 2) + 1;
        $block_min = min($this->query->getPage() - $range, $this->query->getTotalPage() - $min_links);
        $block_high = max($this->query->getPage() + $range, $min_links);

        $left_gap = (($block_min - $anchor - $gap) > 0) ? true : false;
        $right_gap = (($block_high + $anchor + $gap) < $this->query->getTotalPage()) ? true : false;

        $numbers = [];
        if ($left_gap && !$right_gap) {
            $this->numLoop($numbers, 1, $anchor);
            $this->ellipsis($numbers);
            $this->numLoop($numbers, $block_min, $this->query->getTotalPage());
        } elseif ($left_gap && $right_gap) {
            $this->numLoop($numbers, 1, $anchor);
            $this->ellipsis($numbers);
            $this->numLoop($numbers, $block_min, $block_high);
            $this->ellipsis($numbers);
            $this->numLoop($numbers, ($this->query->getTotalPage() - $anchor + 1), $this->query->getTotalPage());
        } elseif (!$left_gap && $right_gap) {
            $this->numLoop($numbers, 1, $block_high);
            $this->ellipsis($numbers);
            $this->numLoop($numbers, ($this->query->getTotalPage() - $anchor + 1), $this->query->getTotalPage());
        } else {
            $this->numLoop($numbers, 1, $this->query->getTotalPage());
        }

        $this->set('numbers', $numbers);
    }
}