<?php declare(strict_types=1);

namespace tiFy\Template\Templates\UserListTable;

use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use tiFy\Contracts\Template\{FactoryDb, FactoryBuilder};
use tiFy\Template\Templates\ListTable\DbBuilder as BaseDbBuilder;
use tiFy\Template\Templates\UserListTable\Contracts\{Db, DbBuilder as DbBuilderContract};
use tiFy\Wordpress\Contracts\Database\UserBuilder;

class DbBuilder extends BaseDbBuilder implements DbBuilderContract
{
    /**
     * Instance de la requête courante en base de données.
     * @var UserBuilder|null
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
     * @inheritDoc
     */
    public function parse(): FactoryBuilder
    {
        parent::parse();

        return $this;
    }

    /**
     * {@inheritDoc}
     *
     * @return UserBuilder
     */
    public function query(): ?EloquentBuilder
    {
        return parent::query();
    }

    /**
     * {@inheritDoc}
     *
     * @return UserBuilder
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
                    /** @var UserBuilder $query */
                    $query->orWhere('user_login', 'like', $term)
                        ->orWhere('user_email', 'like', $term)
                        ->orWhere('user_nicename', 'like', $term)
                        ->orWhere('display_name', 'like', $term);
                });
            });
        }

        return $this->query();
    }

    /**
     * {@inheritDoc}
     *
     * @return UserBuilder
     */
    public function queryWhere(): EloquentBuilder
    {
        parent::queryWhere();

        if ($roles = $this->get('roles')) {
            if (is_string($roles)) {
                $roles = [$roles];
            }

            $this->query()->whereHas('meta', function (EloquentBuilder $query) use ($roles) {
                foreach($roles as $i => $role) {
                    if (!$i) {
                        $query->where('meta_key', $this->db()->getBlogPrefix() . 'capabilities')
                            ->where('meta_value', 'like', "%{$role}%");
                    } else {
                        $query->orWhere('meta_key', $this->db()->getBlogPrefix() . 'capabilities')
                            ->where('meta_value', 'like', "%{$role}%");
                    }
                }
            });
        }

        return $this->query;
    }
}