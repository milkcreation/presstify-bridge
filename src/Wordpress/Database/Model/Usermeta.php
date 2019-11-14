<?php declare(strict_types=1);

namespace tiFy\Wordpress\Database\Model;

use Corcel\Model\Meta\UserMeta as CorcelUsermeta;
use Illuminate\Database\Eloquent\Builder;
use tiFy\Wordpress\Database\Concerns\MetaAwareTrait;

/**
 * @mixin Builder
 */
class Usermeta extends CorcelUsermeta
{
    use MetaAwareTrait;

    /**
     * Nom de qualification de la connexion associé.
     * @var string
     */
    protected $connection = 'wp';

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
