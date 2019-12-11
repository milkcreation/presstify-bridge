<?php declare(strict_types=1);

namespace tiFy\Wordpress\Database\Model;

use Corcel\Model\Meta\TermMeta as CorcelTermmeta;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use tiFy\Wordpress\Database\Concerns\MetaAwareTrait;

/**
 * @mixin Builder
 */
class Termmeta extends CorcelTermmeta
{
    use MetaAwareTrait;

    public function term(): BelongsTo
    {
        return $this->belongsTo(Term::class);
    }
}
