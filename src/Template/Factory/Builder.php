<?php declare(strict_types=1);

namespace tiFy\Template\Factory;

use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use tiFy\Contracts\Template\{FactoryBuilder as FactoryBuilderContract, TemplateFactory};
use tiFy\Support\ParamsBag;

class Builder extends ParamsBag implements FactoryBuilderContract
{
    use FactoryAwareTrait;

    /**
     * Instance du gabarit d'affichage.
     * @var TemplateFactory
     */
    protected $factory;

    /**
     * Sens d'ordonnancement des éléments.
     * @var string|null
     */
    protected $order;

    /**
     * Colonne d'ordonnancement des éléments.
     * @var string|null
     */
    protected $orderby;

    /**
     * Numéro de la page d'affichage courante.
     * @var int|null
     */
    protected $page;

    /**
     * Nombre d'éléments affichés par page.
     * @var int|null
     */
    protected $perPage;

    /**
     * Instance de la requête courante en base de données.
     * @var EloquentBuilder|null
     */
    protected $query;

    /**
     * Mots clefs de recherche.
     * @var string|null
     */
    protected $search;

    /**
     * @inheritDoc
     */
    public function getOrder(): string
    {
        return $this->order;
    }

    /**
     * @inheritDoc
     */
    public function getOrderBy(): string
    {
        return $this->orderby;
    }

    /**
     * @inheritDoc
     */
    public function getPage(): int
    {
        return $this->page;
    }

    /**
     * @inheritDoc
     */
    public function getPerPage(): int
    {
        return $this->perPage;
    }

    /**
     * @inheritDoc
     */
    public function getSearch(): string
    {
        return $this->search;
    }

    /**
     * @inheritDoc
     */
    public function parse(): FactoryBuilderContract
    {
        parent::parse();

        $this->set($this->factory->request()->all());

        if (is_null($this->search)) {
            $this->setSearch((string)$this->get('s', ''));
        }

        if (is_null($this->perPage)) {
            $this->setPerPage($this->get('per_page', 20));
        }

        if (is_null($this->page)) {
            $this->setPage((int)$this->get('paged', 1));
        }

        if (is_null($this->order)) {
            $this->setOrder($this->get('order', 'ASC'));
        }

        if (is_null($this->orderby)) {
            $this->setOrderBy($this->get('orderby', ''));
        }

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function remove($keys): FactoryBuilderContract
    {
        $this->forget($keys);

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setOrder(string $order): FactoryBuilderContract
    {
        $order = strtoupper($order ?: 'ASC');
        $this->order = in_array($order, ['ASC', 'DESC']) ? $order : 'ASC';

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setOrderBy(string $orderby): FactoryBuilderContract
    {
        $this->orderby = $orderby;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setPage(int $page): FactoryBuilderContract
    {
        $this->page = $page ? $page : 1;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setPerPage(int $per_page): FactoryBuilderContract
    {
        $this->perPage = $per_page >= 0 ? $per_page : -1;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setSearch(string $search): FactoryBuilderContract
    {
        $this->search = $search;

        return $this;
    }
}