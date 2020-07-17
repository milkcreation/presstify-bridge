<?php declare(strict_types=1);

namespace tiFy\Support\Traits;

use Psr\Http\Message\UriInterface;
use League\Uri\Contracts\UriInterface as LeagueUri;
use tiFy\Contracts\Routing\UrlFactory;
use tiFy\Support\Proxy\Url;

trait PaginationAwareTrait
{
    /**
     * Instance de l'url de base.
     * {@internal Page d'affichage courante par défaut. }
     * @var UrlFactory|null
     */
    protected $base_url;

    /**
     * Numéro de la page courante.
     * @var int
     */
    protected $current_page = 1;

    /**
     * Nombre d'éléments courant.
     * @var int
     */
    protected $count = 0;

    /**
     * Numéro de la dernière page.
     * @var int
     */
    protected $last_page = 1;

    /**
     * La ligne de démarrage du traitement.
     * @var int
     */
    protected $offset = 0;

    /**
     * Indice de qualification des pages.
     * @var string
     */
    protected $page_index = 'page';

    /**
     * Nombre d'éléments par page.
     * @var int|null
     */
    protected $per_page;

    /**
     * Indicateur d'url basée sur un segment.
     * {@internal false : {{ base_url }}/?page={{ num }} | true :{{ base_url }}/page/{{ num }} }
     *
     * @var bool
     */
    protected $segment_url = false;

    /**
     * Nombre total d'éléments.
     * @var int
     */
    protected $total = 0;

    /**
     * Récupération de l'url de base.
     *
     * @return UrlFactory|null
     */
    public function getBaseUrl(): ?UrlFactory
    {
        return $this->base_url;
    }

    /**
     * Récupération du nombre d'éléments courant.
     *
     * @return int
     */
    public function getCount(): int
    {
        return $this->count;
    }

    /**
     * Récupération de la page courante.
     *
     * @return int
     */
    public function getCurrentPage(): int
    {
        return $this->current_page;
    }

    /**
     * Récupération du numéro de la dernière page.
     *
     * @return int
     */
    public function getLastPage(): int
    {
        return $this->last_page;
    }

    /**
     * Récupération de la ligne de démarrage du traitement.
     *
     * @return int
     */
    public function getOffset(): int
    {
        return $this->offset;
    }

    /**
     * Récupération de l'indice de qualification des pages.
     *
     * @return string
     */
    public function getPageIndex(): string
    {
        return $this->page_index;
    }

    /**
     * Récupération de l'url associé à un numéro de page
     *
     * @param int $num
     *
     * @return string
     */
    public function getPageNumUrl(int $num): string
    {
        if (!$this->getBaseUrl()) {
            $this->setBaseUrl();
        }

        $url = clone $this->getBaseUrl();

        if (preg_match('/%d/', $url->decoded())) {
            return urlencode(sprintf($url->decoded(), $num));
        } elseif ($this->isSegmentUrl()) {
            $url = $url->deleteSegment("/{$this->getPageIndex()}/\d+");

            return $num > 1
                ? $url->appendSegment("/{$this->getPageIndex()}/{$num}")->render() : $url->render();
        } else {
            $url = $url->without([$this->getPageIndex()]);

            return $num > 1 ? $url->with([$this->getPageIndex() => $num])->render() : $url->render();
        }
    }

    /**
     * Récupération du nombre d'élément par page.
     *
     * @return int|null
     */
    public function getPerPage(): ?int
    {
        return $this->per_page;
    }

    /**
     * Récupération du nombre total d'éléments.
     *
     * @return int
     */
    public function getTotal(): int
    {
        return $this->total;
    }

    /**
     * Vérifie si les urls de pagination sont basée sur des segments.
     *
     * @return bool
     */
    public function isSegmentUrl(): bool
    {
        return $this->segment_url;
    }

    /**
     * Définition de l'url de base utilisé pour les liens de pagination.
     * {@internal %d représente le numéro de page.}
     *
     * @param UrlFactory|UriInterface|LeagueUri|string|null $base_url
     *
     * @return static
     */
    public function setBaseUrl($base_url = null): self
    {
        if (!$base_url instanceof UrlFactory) {
            $this->base_url = is_null($base_url) ? Url::current() : Url::set($base_url);
        }

        return $this;
    }

    /**
     * Définition du nombre d'éléments courants trouvés.
     *
     * @param int $count
     *
     * @return static
     */
    public function setCount(int $count): self
    {
        $this->count = $count;

        return $this;
    }

    /**
     * Définition de la page courante de récupération des éléments.
     *
     * @param int $page
     *
     * @return static
     */
    public function setCurrentPage(int $page): self
    {
        $this->current_page = $page > 0 ? $page : 1;

        return $this;
    }

    /**
     * Définition du numéro de la dernière page.
     *
     * @param int $last_page
     *
     * @return static
     */
    public function setLastPage(int $last_page): self
    {
        $this->last_page = $last_page;

        return $this;
    }

    /**
     * Définition de la ligne de démarrage du traitement de récupération des éléments.
     *
     * @param int $offset
     *
     * @return static
     */
    public function setOffset(int $offset): self
    {
        $this->offset = $offset;

        return $this;
    }

    /**
     * Définition de l'indice de qualification des page.
     *
     * @param string $index
     *
     * @return static
     */
    public function setPageIndex(string $index = 'page'): self
    {
        $this->page_index = $index;

        return $this;
    }

    /**
     * Définition du nombre total d'éléments par page.
     *
     * @param int|null $per_page
     *
     * @return static
     */
    public function setPerPage(?int $per_page = null): self
    {
        $this->per_page = $per_page > 0 ? $per_page : null;

        return $this;
    }

    /**
     * Activation de l'url par segment.
     *
     * @param bool $use
     *
     * @return static
     */
    public function setSegmentUrl(bool $use): self
    {
        $this->segment_url = $use;

        return $this;
    }

    /**
     * Définition du nombre total d'éléments.
     *
     * @param int $total
     *
     * @return static
     */
    public function setTotal(int $total): self
    {
        $this->total = $total;

        return $this;
    }

    /**
     * Récupération de la liste des arguments.
     *
     * @return array
     */
    public function toArray(): array
    {
        return [
            'base_url'     => $this->base_url,
            'current_page' => $this->current_page,
            'count'        => $this->count,
            'last_page'    => $this->last_page,
            'offset'       => $this->offset,
            'page_index'   => $this->page_index,
            'per_page'     => $this->per_page,
            'segment_url'  => $this->segment_url,
            'total'        => $this->total
        ];
    }
}