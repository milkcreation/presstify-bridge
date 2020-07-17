<?php declare(strict_types=1);

namespace tiFy\Wordpress\Template\Templates\FileManager;

use tiFy\Template\Templates\FileManager\{Contracts\FileInfo, IconSet as BaseIconSet};
use tiFy\Support\Proxy\Partial;

class IconSet extends BaseIconSet
{
    /**
     * @inheritDoc
     */
    public function file(FileInfo $file): string
    {
        if ($file->isDir()) {
            $class = 'dashicons dashicons-category';
        } else {
            switch ($type = wp_ext2type($file->getExtension())) {
                case 'archive' :
                case 'audio' :
                case 'code' :
                case 'document' :
                case 'interactive' :
                case 'spreadsheet' :
                case 'text' :
                case 'video' :
                    $class = "dashicons dashicons-media-{$type}";
                    break;
                case 'image' :
                    $class = 'dashicons dashicons-format-image';
                    break;
                default :
                    $class = 'dashicons dashicons-media-default';
                    break;
            }
        }

        return Partial::get('tag', [
            'tag'   => 'span',
            'attrs' => [
                'class' => 'FileManager-icon FileManager-icon--file' . ($class ? ' ' . $class : '')
            ]
        ])->render();
    }
}