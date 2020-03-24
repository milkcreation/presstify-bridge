<?php declare(strict_types=1);

namespace tiFy\Wordpress\Database\Model;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin Builder
 */
class Link extends Model
{
    /**
     * Nom de qualification de la clé primaire.
     * @var string
     */
    protected $primaryKey = 'link_id';

    /**
     * Nom de qualification de la table associée.
     * @var string
     */
    protected $table = 'links';

    /**
     * Nom de qualification de la colonne de gestion de la date de création.
     * @var string
     */
    const CREATED_AT = null;

    /**
     * Nom de qualification de la colonne de gestion de la date de création.
     * @var string
     */
    const UPDATED_AT = 'link_updated';
}
