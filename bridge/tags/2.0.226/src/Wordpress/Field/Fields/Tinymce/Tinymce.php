<?php declare(strict_types=1);

namespace tiFy\Wordpress\Field\Fields\Tinymce;

use tiFy\Contracts\Field\FieldFactory as FieldFactoryContract;
use tiFy\Field\Fields\Tinymce\Tinymce as BaseTinymce;

class Tinymce extends BaseTinymce
{
    /**
     * @inheritDoc
     */
    public function parse(): FieldFactoryContract
    {
        parent::parse();

        return $this;
    }
}