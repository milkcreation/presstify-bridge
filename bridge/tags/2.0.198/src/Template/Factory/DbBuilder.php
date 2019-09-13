<?php declare(strict_types=1);

namespace tiFy\Template\Factory;

use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use tiFy\Contracts\Template\{FactoryDbBuilder as FactoryDbBuilderContract, FactoryDb};

class DbBuilder extends Builder implements FactoryDbBuilderContract
{
    /**
     * Instance de la requête courante en base de données.
     * @var EloquentBuilder|null
     */
    protected $query;

    /**
     * @inheritDoc
     */
    public function db(): ?FactoryDb
    {
        return $this->factory->db();
    }

    /**
     * @inheritDoc
     */
    public function getOrderBy(): string
    {
        return $this->orderby ?: $this->db()->getKeyName();
    }

    /**
     * @inheritDoc
     */
    public function query(): ?EloquentBuilder
    {
        if (is_null($this->query)) {
            $this->query = $this->db() ? $this->db()::query() : null;
        }

        return $this->query;
    }

    /**
     * @inheritDoc
     */
    public function queryLimit(): EloquentBuilder
    {
        return $this->query()->forPage($this->getPage(), $this->getPerPage());
    }

    /**
     * @inheritDoc
     */
    public function queryOrder(): EloquentBuilder
    {
        return $this->query()->orderBy($this->getOrderBy(), $this->getOrder());
    }

    /**
     * @inheritDoc
     */
    public function querySearch(): EloquentBuilder
    {
        if ($terms = $this->getSearch()) {
            $this->query()->where($this->db()->getKeyName(), 'like', "%{$terms}%");
        }

        return $this->query();
    }

    /**
     * @inheritDoc
     */
    public function queryWhere(): EloquentBuilder
    {
        foreach ($this->all() as $k => $v) {
            if ($this->db()->hasColumn($k)) {
                is_array($v) ? $this->query()->whereIn($k, $v) : $this->query()->where($k, $v);
            }
        }

        return $this->query();
    }

    /**
     * @inheritDoc
     */
    public function resetQuery(): FactoryDbBuilderContract
    {
        $this->query = null;

        return $this;
    }
}