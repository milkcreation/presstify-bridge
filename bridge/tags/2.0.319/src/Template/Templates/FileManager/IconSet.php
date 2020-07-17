<?php declare(strict_types=1);

namespace tiFy\Template\Templates\FileManager;

use tiFy\Support\ParamsBag;
use tiFy\Template\Factory\FactoryAwareTrait;
use tiFy\Template\Templates\FileManager\Contracts\{FileManager, IconSet as IconSetContract, FileInfo};

class IconSet extends ParamsBag implements IconSetContract
{
    use FactoryAwareTrait;

    /**
     * Instance du gabarit d'affichage.
     * @var FileManager
     */
    protected $factory;

    /**
     * Délégation d'appel de récupération d'un icône.
     *
     * @param string $name Nom de qualification de l'icône.
     * @param array $arguments Liste des variables passées en argument lorsque la méthode existe.
     *
     * @return string|null
     */
    public function __call($name, $arguments): ?string
    {
        if ($this->has($name)) {
            if (method_exists($this, $name)) {
                return $this->$name(...$arguments);
            } else {
                return $this->render(['attrs' => ['class' => 'FileManager-icon ' . $this->get($name)]]);
            }
        }
        return null;
    }

    /**
     * @inheritDoc
     */
    public function defaults()
    {
        return [
            'archive'    => 'fa fa-file-archive-o',
            'audio'      => 'fa-file-audio-o',
            'code'       => 'fa fa-file-code-o',
            'close'      => 'fa fa-times',
            'collapse'   => 'fa fa-minus-square',
            'directory'  => 'fa fa-folder',
            'download'   => 'fa fa-download',
            'excel'      => 'fa fa-file-excel-o',
            'expand'     => 'fa fa-plus-square',
            'file'       => 'fa fa-file',
            'fullscreen' => 'fa fa-expand',
            'grid'       => 'fa fa-th-large',
            'home'       => 'fa fa-home',
            'image'      => 'fa fa-file-image-o',
            'list'       => 'fa fa-list-alt',
            'next'       => 'fa fa-chevron-right',
            'pdf'        => 'fa fa-file-pdf-o',
            'prev'       => 'fa fa-chevron-left',
            'powerpoint' => 'fa fa-file-powerpoint-o',
            'preview'    => 'fa fa-eye',
            'spinner'    => 'fa fa-spinner fa-pulse fa-fw',
            'text'       => 'fa fa-file-text-o',
            'video'      => 'fa fa-file-video-o',
            'word'       => 'fa fa-file-word-o',
            'upload'     => 'fa fa-cloud-upload'
        ];
    }

    /**
     * {@inheritDoc}
     *
     * @see https://developer.mozilla.org/fr/docs/Web/HTTP/Basics_of_HTTP/MIME_types/Complete_list_of_MIME_types
     */
    public function file(FileInfo $file): string
    {
        $class = '';

        switch ($mime = $file->getMimetype()) {
            case 'application/pdf' :
                $class = $this->get('pdf');
                break;
            case 'application/msword' :
            case 'application/vnd.ms-word' :
            case 'application/vnd.openxmlformats-officedocument.wordprocessingml.document' :
            case 'application/vnd.oasis.opendocument.text':
            case 'application/vnd.openxmlformats-officedocument.wordprocessingml' :
                $class = $this->get('word');
                break;
            case 'application/vnd.ms-excel' :
            case 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' :
            case 'application/vnd.openxmlformats-officedocument.spreadsheetml' :
            case 'application/vnd.oasis.opendocument.spreadsheet' :
                $class = $this->get('excel');
                break;
            case 'application/vnd.ms-powerpoint' :
            case 'application/vnd.openxmlformats-officedocument.presentationml' :
            case 'application/vnd.oasis.opendocument.presentation' :
                $class = $this->get('powerpoint');
                break;
            case 'text/plain' :
                $class = $this->get('text');
                break;
            case 'text/html' :
            case 'text/x-php' :
            case 'application/json' :
                $class = $this->get('code');
                break;
            case 'application/gzip' :
            case 'application/zip' :
                $class = $this->get('archive');
                break;
        }

        if (!$class) {
            $type = $file->getTypeOfMime();
            if ($this->has($type)) {
                $class = $this->get($type);
            }
        }

        if (!$class) {
            $class = $this->get('file');
        }

        return $this->render([
            'attrs' => ['class' => 'FileManager-icon FileManager-icon--file' . ($class ? ' ' . $class : '')]
        ]);
    }

    /**
     * @inheritDoc
     */
    public function render(array $attrs): string
    {
        return partial('tag', array_merge([
            'attrs' => [
                'class' => 'FileManager-icon'
            ],
            'tag'   => 'span',
        ], $attrs))->render();
    }
}