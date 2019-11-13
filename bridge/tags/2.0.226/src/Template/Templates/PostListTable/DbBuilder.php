<?php declare(strict_types=1);

namespace tiFy\Template\Templates\PostListTable;

use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use tiFy\Contracts\Template\FactoryDb;
use tiFy\Template\Templates\ListTable\DbBuilder as BaseDbBuilder;
use tiFy\Template\Templates\PostListTable\Contracts\{Db, DbBuilder as DbBuilderContract};
use tiFy\Wordpress\Contracts\Database\PostBuilder;

class DbBuilder extends BaseDbBuilder implements DbBuilderContract
{
    /**
     * Instance de la requête courante en base de données.
     * @var PostBuilder|null
     */
    protected $query;

    /**
     * {@inheritDoc}
     *
     * @return Db
     */
    public function db(): ?FactoryDb
    {
        return parent::db();
    }

    /**
     * {@inheritDoc}
     *
     * @return PostBuilder
     */
    public function query(): ?EloquentBuilder
    {
        return parent::query();
    }

    /**
     * {@inheritDoc}
     *
     * @return PostBuilder
     */
    public function queryLimit(): EloquentBuilder
    {
        return parent::queryLimit();
    }


    /**
     * {@inheritDoc}
     *
     * @return PostBuilder
     */
    public function queryOrder(): EloquentBuilder
    {
        return parent::queryOrder();
    }

    /**
     * {@inheritDoc}
     *
     * @return PostBuilder
     */
    public function querySearch(): EloquentBuilder
    {
        if ($term = $this->getSearch()) {
            $terms = is_string($term) ? explode(' ', $term) : $term;

            $terms = collect($terms)->map(function ($term) {
                return trim(str_replace('%', '', $term));
            })->filter()->map(function ($term) {
                return '%' . $term . '%';
            });

            if ($terms->isEmpty()) {
                return $this->query();
            }

            return $this->query()->where(function (EloquentBuilder $query) use ($terms) {
                $terms->each(function ($term) use ($query) {
                    /** @var PostBuilder $query */
                    $query->orWhere('post_title', 'like', $term)
                        ->orWhere('post_excerpt', 'like', $term)
                        ->orWhere('post_content', 'like', $term);
                });
            });
        }

        return $this->query();
    }

    /**
     * {@inheritDoc}
     *
     * @return PostBuilder
     */
    public function queryWhere(): EloquentBuilder
    {
        parent::queryWhere();

        foreach ($this->all() as $k => $v) {
            if ($this->db()->hasColumn($k)) {
                is_array($v) ? $this->query()->whereIn($k, $v) : $this->query()->where($k, $v);
            }
        }

        foreach($this->get('meta', []) as $k => $v) {
            if (!is_null($v)) {
                $this->query()->whereHas('meta', function (EloquentBuilder $query) use ($k, $v) {
                    $query->where('meta_key', $k)->whereIn('meta_value', $v);
                });
            }
        }

        foreach ($this->get('tax', []) as $taxonomy => $terms) {
            if (!is_null($terms)) {
                $this->query()
                    ->where('taxonomy', $taxonomy)
                    ->whereHas('taxonomies', function (EloquentBuilder $query) use ($taxonomy, $terms) {
                        $query->whereHas('term', function (EloquentBuilder $query) use ($terms) {
                            $query->whereIn('slug', is_array($terms) ? $terms : [$terms]);
                        });
                    });
            }
        }

        return $this->query();
    }
}