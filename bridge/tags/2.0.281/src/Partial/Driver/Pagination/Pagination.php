<?php declare(strict_types=1);

namespace tiFy\Partial\Driver\Pagination;

use tiFy\Contracts\Partial\{
    Pagination as PaginationContract,
    PaginationQuery as PaginationQueryContract,
    PartialDriver as PartialDriverContract
};
use tiFy\Partial\PartialDriver;

class Pagination extends PartialDriver implements PaginationContract
{
    /**
     * Instance du gestionnaire de requête de récupération des éléments.
     * @var PaginationQueryContract|null
     */
    protected $query;

    /**
     * {@inheritDoc}
     *
     * @return array {
     * @var array $attrs Attributs HTML du champ.
     * @var string $after Contenu placé après le champ.
     * @var string $before Contenu placé avant le champ.
     * @var array $viewer Liste des attributs de configuration du pilote d'affichage.
     * @var string|array|null $url Url de pagination {
     *      @var string|null $base l'url peux contenir %d en remplacement du numéro de page. Si null, url courante.
     *      @var bool $segment Activation de la réécriture depuis un segment de l'url|depuis des arguments de requête.
     *      @var string $index Indice de qualification d'une page
     * }
     * @var array $links {
     *      @var boolean|array $first Activation du lien vers la première page|Liste d'attributs.
     *      @var boolean|array $last Activation du lien vers la dernière page|Liste d'attributs.
     *      @var boolean|array $previous Activation du lien vers la page précédente|Liste d'attributs.
     *      @var boolean|array $next Activation du lien vers la page suivante|Liste d'attributs.
     *      @var boolean|array $numbers Activation de l'affichage de la numérotation des pages|Liste d'attributs {
     *          @var int $range
     *          @var int $anchor
     *          @var int $gap
     *      }
     * }
     * @var array|PaginationQuery|object $query
     */
    public function defaults(): array
    {
        return [
            'attrs'  => [],
            'after'  => '',
            'before' => '',
            'viewer' => [],
            'url'    => null,
            'links'  => [
                'first'    => true,
                'last'     => true,
                'previous' => true,
                'next'     => true,
                'numbers'  => true,
            ],
            'query'  => null,
        ];
    }

    /**
     * @inheritDoc
     */
    public function ellipsis(array &$numbers): void
    {
        $numbers[] = [
            'tag'     => 'span',
            'content' => '...',
            'attrs'   => 'Pagination-itemEllipsis',
        ];
    }

    /**
     * @inheritDoc
     */
    public function numLoop(array &$numbers, int $start, int $end): void
    {
        for ($num = $start; $num <= $end; $num++) {
            if ($num === 1 && !$this->query->getCurrentPage()) {
                $current = 'true';
            } elseif ($this->query->getCurrentPage() === $num) {
                $current = 'true';
            } else {
                $current = 'false';
            }

            $numbers[] = [
                'tag'     => 'a',
                'content' => $num,
                'attrs'   => [
                    'class'        => 'Pagination-itemPage Pagination-itemPage--link',
                    'href'         => $this->query->getPageNumUrl($num),
                    'aria-current' => $current,
                ],
            ];
        }
    }

    /**
     * @inheritDoc
     */
    public function parse(): PartialDriverContract
    {
        parent::parse();

        $this->parseQuery()->parseUrl();

        $this->query->parse();

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
    public function parseLinks(): PartialDriverContract
    {
        $defaults = [
            'first'    => [
                'tag'     => 'a',
                'content' => '&laquo;',
                'attrs'   => [
                    'class' => 'Pagination-itemPage Pagination-itemPage--link',
                    'href'  => $this->query->getPageNumUrl(1),
                ],
            ],
            'last'     => [
                'tag'     => 'a',
                'content' => '&raquo;',
                'attrs'   => [
                    'class' => 'Pagination-itemPage Pagination-itemPage--link',
                    'href'  => $this->query->getPageNumUrl($this->query->getLastPage()),
                ],
            ],
            'previous' => [
                'tag'     => 'a',
                'content' => '&lsaquo;',
                'attrs'   => [
                    'class' => 'Pagination-itemPage Pagination-itemPage--link',
                    'href'  => $this->query->getPageNumUrl($this->query->getCurrentPage() - 1),
                ],
            ],
            'next'     => [
                'tag'     => 'a',
                'content' => '&rsaquo;',
                'attrs'   => [
                    'class' => 'Pagination-itemPage Pagination-itemPage--link',
                    'href'  => $this->query->getPageNumUrl($this->query->getCurrentPage() + 1),
                ],
            ],
        ];

        foreach ($defaults as $link => $default) {
            $attrs = $this->get("links.{$link}", []);

            if ($attrs === false) {
                $attrs = [];
            } elseif ($attrs === true) {
                $attrs = $default;
            } elseif (is_string($attrs)) {
                $attrs = array_merge($default, ['content' => $attrs]);
            } else {
                $attrs = array_merge($default, $attrs);
            }

            $this->set("links.{$link}", $attrs);
        }

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function parseNumbers(): PartialDriverContract
    {
        $range = intval($this->get('links.numbers.range', 2));
        $anchor = intval($this->get('links.numbers.anchor', 3));
        $gap = intval($this->get('links.numbers.gap', 1));

        $min_links = ($range * 2) + 1;
        $block_min = min($this->query->getCurrentPage() - $range, $this->query->getLastPage() - $min_links);
        $block_high = max($this->query->getCurrentPage() + $range, $min_links);

        $left_gap = (($block_min - $anchor - $gap) > 0) ? true : false;
        $right_gap = (($block_high + $anchor + $gap) < $this->query->getLastPage()) ? true : false;

        $numbers = [];
        if ($left_gap && !$right_gap) {
            $this->numLoop($numbers, 1, $anchor);
            $this->ellipsis($numbers);
            $this->numLoop($numbers, $block_min, $this->query->getLastPage());
        } elseif ($left_gap && $right_gap) {
            $this->numLoop($numbers, 1, $anchor);
            $this->ellipsis($numbers);
            $this->numLoop($numbers, $block_min, $block_high);
            $this->ellipsis($numbers);
            $this->numLoop($numbers, ($this->query->getLastPage() - $anchor + 1), $this->query->getLastPage());
        } elseif (!$left_gap && $right_gap) {
            $this->numLoop($numbers, 1, $block_high);
            $this->ellipsis($numbers);
            $this->numLoop($numbers, ($this->query->getLastPage() - $anchor + 1), $this->query->getLastPage());
        } else {
            $this->numLoop($numbers, 1, $this->query->getLastPage());
        }

        $this->set('numbers', $numbers);

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function parseQuery(): PartialDriverContract
    {
        $this->query = $this->pull('query', []);
        if (!$this->query instanceof PaginationQueryContract) {
            $this->query = (new PaginationQuery($this->query));
        }

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function parseUrl(): PartialDriverContract
    {
        if ($this->has('url.base')) {
            $this->query->setBaseUrl($this->get('url.base'));
        }

        if ($this->has('url.segment')) {
            $this->query->setSegmentUrl($this->get('url.segment'));
        }

        if ($this->has('url.index')) {
            $this->query->setPageIndex($this->get('url.index'));
        }

        if (!is_array($this->get('url'))) {
            $this->query->setBaseUrl($this->get('url', null));
        }

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function query(): ?PaginationQueryContract
    {
        return $this->query;
    }
}