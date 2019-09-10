<?php declare(strict_types=1);

namespace tiFy\Template\Factory;

use Illuminate\Database\Eloquent\{Builder, Model};
use tiFy\Contracts\Template\{FactoryDb as FactoryDbContract, TemplateFactory};
use tiFy\Database\Concerns\ColumnsAwareTrait;

/**
 * @mixin Builder
 */
class Db extends Model implements FactoryDbContract
{
    use ColumnsAwareTrait, FactoryAwareTrait;

    /**
     * Instance du gabarit d'affichage.
     * @var TemplateFactory
     */
    protected $factory;
}