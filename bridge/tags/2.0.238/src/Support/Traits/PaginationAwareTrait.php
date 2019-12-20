<?php declare(strict_types=1);

namespace tiFy\Support\Traits;

trait PaginationAwareTrait
{
    /**
     * Nombre d'éléments courant.
     * @var int
     */
    protected $count = 0;

    /**
     * Numéro de la dernière page.
     * @var int
     */
    protected $lastPage = 1;

    /**
     * La ligne de démarrage du traitement.
     * @var int
     */
    protected $offset = 0;

    /**
     * Numéro de la page courante.
     * @var int
     */
    protected $page = 1;

    /**
     * Nombre d'éléments par page.
     * @var int|null
     */
    protected $perPage;

    /**
     * Colonne de clés primaires d'indexation des éléments.
     * @var string|int|null
     */
    protected $primary;

    /**
     * Nombre total d'éléments.
     * @var int
     */
    protected $total = 0;

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
     * Récupération du numéro de la dernière page.
     *
     * @return int
     */
    public function getLastPage(): int
    {
        return $this->lastPage;
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
     * Récupération de la page courante.
     *
     * @return int
     */
    public function getPage(): int
    {
        return $this->page;
    }

    /**
     * Récupération du nombre d'élément par page.
     *
     * @return int|null
     */
    public function getPerPage(): ?int
    {
        return $this->perPage;
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
     * Définition du numéro de la dernière page.
     *
     * @param int $last_page
     *
     * @return static
     */
    public function setLastPage(int $last_page): self
    {
        $this->lastPage = $last_page;

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
     * Définition de la page courante de récupération des éléments.
     *
     * @param int $page
     *
     * @return static
     */
    public function setPage(int $page): self
    {
        $this->page = $page > 0 ? $page : 1;

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
        $this->perPage = $per_page > 0 ? $per_page : null;

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
}