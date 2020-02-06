<?php declare(strict_types=1);

namespace tiFy\Template\Templates\PostListTable\Contracts;

use Corcel\Model\Builder\PostBuilder;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use tiFy\Template\Templates\ListTable\Contracts\DbBuilder as BaseDbBuilder;

interface DbBuilder extends BaseDbBuilder
{
    /**
     * {@inheritDoc}
     *
     * @return PostBuilder
     */
    public function query(): ?EloquentBuilder;
}