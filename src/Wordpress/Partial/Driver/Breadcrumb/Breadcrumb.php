<?php declare(strict_types=1);

namespace tiFy\Wordpress\Partial\Driver\Breadcrumb;

use tiFy\Contracts\Partial\BreadcrumbCollection as BreadcrumbCollectionContract;
use tiFy\Partial\Driver\Breadcrumb\Breadcrumb as BaseBreadcrumb;
use tiFy\Wordpress\Contracts\Partial\PartialDriver as PartialDriverContract;

class Breadcrumb extends BaseBreadcrumb implements PartialDriverContract
{
    /**
     * @inheritDoc
     */
    public function collection(): BreadcrumbCollectionContract
    {
        if (is_null($this->collection)) {
            $this->collection = new BreadcrumbCollection($this);
        }

        return $this->collection;
    }
}