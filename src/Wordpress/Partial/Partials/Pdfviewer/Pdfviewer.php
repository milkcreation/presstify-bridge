<?php declare(strict_types=1);

namespace tiFy\Wordpress\Partial\Partials\Pdfviewer;

use tiFy\Contracts\Partial\PartialFactory as PartialFactoryContract;
use tiFy\Partial\Partials\Pdfviewer\Pdfviewer as BasePdfviewer;

class Pdfviewer extends BasePdfviewer
{
    /**
     * @inheritDoc
     */
    public function parse(): PartialFactoryContract
    {
        parent::parse();

        $src = $this->get('src');
        if (is_numeric($src)) {
            $src = wp_get_attachment_url($src);

            $this->set([
                'attrs.data-options.src' => $src,
                'src'                    => $src,
            ]);
        }

        return $this;
    }
}