<?php declare(strict_types=1);

namespace tiFy\Partial\Driver\Pagination;

use tiFy\Contracts\Partial\PaginationQuery as PaginationQueryContract;
use tiFy\Support\Collection;

class PaginationQuery extends Collection implements PaginationQueryContract
{
    /**
     * Nombre de résultats trouvés.
     * @var int
     */
    protected $founds = 0;

    /**
     * Nombre d'éléments de décalage.
     * @var int
     */
    protected $offset = 0;

    /**
     * Numéro de page courante.
     * @var int
     */
    protected $page = 0;

    /**
     * Nombre d'éléments par page.
     * @var int
     */
    protected $per_page = 10;

    /**
     * Nombre total de page.
     * @var int
     */
    protected $total_page = 0;

    /**
     * @inheritDoc
     */
    public function getPage(): int
    {
        return $this->page ? : 1;
    }

    /**
     * @inheritDoc
     */
    public function getTotalPage(): int
    {
        return (int)$this->total_page;
    }

    /**
     * @inheritDoc
     */
    public function setPagination(): PaginationQueryContract
    {
        if ($this->founds) {
            $this->total_page = $this->offset
                ? ceil(
                    ($this->founds + (($this->per_page * ($this->page - 1)) - $this->offset)) / $this->per_page
                )
                : ceil($this->founds / $this->per_page);
        } else {
            $this->total_page = 0;
        }

        return $this;
    }
}