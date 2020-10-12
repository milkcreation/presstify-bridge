<?php declare(strict_types=1);

namespace tiFy\Form\Addon\Record;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use tiFy\Database\MetaModel;

class RecordMetaModel extends MetaModel
{
    /**
     * Nom de qualification de la table associée.
     * @var string
     */
    protected $table = 'tify_forms_recordmeta';

    /**
     * @return BelongsTo
     */
    public function record()
    {
        return $this->belongsTo(RecordModel::class);
    }
}