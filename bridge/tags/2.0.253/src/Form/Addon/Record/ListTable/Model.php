<?php declare(strict_types=1);

namespace tiFy\Form\Addon\Record\ListTable;

use tiFy\Form\Concerns\AddonAwareTrait;
use Illuminate\Database\Query\Builder;
use tiFy\Form\Addon\Record\RecordModel;

class Model extends RecordModel
{
    use AddonAwareTrait;

    /**
     * @return Builder
     */
    public function newQuery()
    {
        return $this->form() ? parent::newQuery()->where('form_id', $this->form()->name()) : parent::newQuery();
    }
}