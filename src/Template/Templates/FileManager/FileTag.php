<?php declare(strict_types=1);

namespace tiFy\Template\Templates\FileManager;

use tiFy\Template\Factory\FactoryAwareTrait;
use tiFy\Template\Templates\FileManager\Contracts\{FileManager, FileInfo, FileTag as FileTagContract};

class FileTag implements FileTagContract
{
    use FactoryAwareTrait;

    /**
     * Instance du gabarit d'affichage.
     * @var FileManager
     */
    protected $factory;

    /**
     * Instance du fichier associé.
     * @var FileInfo
     */
    protected $file;

    /**
     * Liste des mots clef associés à un fichier.
     * @var string[]
     */
    protected $tags = [];

    /**
     * @inheritDoc
     */
    public function get(): array
    {
        return $this->tags;
    }

    /**
     * @inheritDoc
     */
    public function has($tag): bool
    {
        $tags = is_array($tag) ? $tag : [$tag];

        foreach ($tags as $t) {
            if (in_array($t, $this->tags)) {
                return true;
            }
        }
        return false;
    }

    /**
     * @inheritDoc
     */
    public function parse(): FileTagContract
    {
        $tags = [];

        switch ($this->file->getMimetype()) {
            case 'application/pdf' :
                $tags = ['document', 'pdf'];
                break;
            case 'application/msword' :
            case 'application/vnd.ms-word' :
            case 'application/vnd.openxmlformats-officedocument.wordprocessingml.document' :
            case 'application/vnd.oasis.opendocument.text':
            case 'application/vnd.openxmlformats-officedocument.wordprocessingml' :
                $tags = ['document', 'word'];
                break;
            case 'application/vnd.ms-excel' :
            case 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' :
            case 'application/vnd.openxmlformats-officedocument.spreadsheetml' :
            case 'application/vnd.oasis.opendocument.spreadsheet' :
                $tags = ['document', 'excel', 'spreadsheet'];
                break;
            case 'application/vnd.ms-powerpoint' :
            case 'application/vnd.openxmlformats-officedocument.presentationml' :
            case 'application/vnd.oasis.opendocument.presentation' :
                $tags = ['document', 'powerpoint', 'presentation'];
                break;
            case 'text/csv' :
                $tags = ['document', 'csv'];
                break;
            case 'text/plain' :
                $tags = ['document', 'plain'];
                break;
            case 'text/html' :
            case 'text/x-php' :
            case 'application/json' :
                $tags = ['code'];
                break;
            case 'application/gzip' :
            case 'application/zip' :
                $tags = ['archive'];
                break;
        }

        array_push($tags, $this->file->getTypeOfMime());

        return $this->set($tags);
    }

    /**
     * @inheritDoc
     */
    public function set($tag): FileTagContract
    {
        $tags = is_array($tag) ? $tag : [$tag];

        foreach ($tags as $t) {
            array_push($this->tags, $t);
        }
        array_unique($this->tags);

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setFile(FileInfo $file): FileTagContract
    {
        $this->file = $file;

        return $this->reset()->parse();
    }

    /**
     * @inheritDoc
     */
    public function reset(): FileTagContract
    {
        $this->tags = [];

        return $this;
    }
}