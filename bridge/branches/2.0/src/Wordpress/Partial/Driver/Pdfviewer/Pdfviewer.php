<?php declare(strict_types=1);

namespace tiFy\Wordpress\Partial\Driver\Pdfviewer;

use tiFy\Contracts\Partial\PartialDriver as PartialDriverContract;
use tiFy\Partial\Driver\Pdfviewer\Pdfviewer as BasePdfviewer;

class Pdfviewer extends BasePdfviewer
{
    /**
     * @inheritDoc
     */
    public function parse(): PartialDriverContract
    {
        parent::parse();

        $src = $this->get('src');
        if (is_numeric($src) && ($src = wp_get_attachment_url($src))) {
            $this->set([
                'attrs.data-options.src' => $src,
                'src'                    => $src,
            ]);
        }

        return $this;
    }
}