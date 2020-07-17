<?php declare(strict_types=1);

namespace tiFy\Http;

use Illuminate\Http\UploadedFile as BaseUploadedFile;
use tiFy\Support\{Arr, Proxy\Storage};

class UploadedFile extends BaseUploadedFile
{
    /**
     * @inheritDoc
     */
    public function storeAs($path, $name, $options = [])
    {
        $options = $this->parseOptions($options);

        $disk = Arr::pull($options, 'disk');

        return Storage::disk($disk)->putUploaded($path, $this, $name, $options);
    }
}