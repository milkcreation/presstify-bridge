<?php declare(strict_types=1);

namespace tiFy\Filesystem;

use League\Flysystem\{AdapterInterface, Cached\CachedAdapter, Filesystem as BaseFilesystem};
use Symfony\Component\HttpFoundation\StreamedResponse;
use SplFileInfo;
use tiFy\Contracts\Filesystem\Filesystem as FilesystemContract;
use tiFy\Http\UploadedFile;
use tiFy\Support\Str;

class Filesystem extends BaseFilesystem implements FilesystemContract
{
    /**
     * @inheritDoc
     */
    public function download(string $path, ?string $name = null, array $headers = []): StreamedResponse
    {
        return $this->response($path, $name, $headers, 'attachment');
    }

    /**
     * @inheritDoc
     */
    public function getRealAdapter(): AdapterInterface
    {
        $disk = $this->getAdapter();

        return $disk instanceof CachedAdapter ? $disk->getAdapter() : $disk;
    }

    /**
     * Enregistrement d'un fichier téléchargé.
     *
     * @param string  $path
     * @param UploadedFile $file
     * @param string  $name
     * @param array  $options
     *
     * @return string|false
     */
    public function putUploaded(string $path, SplFileInfo $file, string $name, array $options = [])
    {
        $stream = fopen($file->getRealPath(), 'r');

        $result = $this->put(
            $path = trim($path.'/'.$name, '/'), $stream, $options
        );

        if (is_resource($stream)) {
            fclose($stream);
        }

        return $result ? $path : false;
    }

    /**
     * @inheritDoc
     */
    public function response(
        string $path,
        ?string $name = null,
        array $headers = [],
        $disposition = 'inline'
    ): StreamedResponse {
        $response = new StreamedResponse();
        $filename = $name ?? basename($path);

        $disposition = $response->headers->makeDisposition($disposition, $filename, Str::ascii($name ? : $filename));
        $response->headers->replace($headers + [
                'Content-Type'        => $this->getMimeType($path),
                'Content-Length'      => $this->getSize($path),
                'Content-Disposition' => $disposition,
            ]);

        $response->setCallback(function () use ($path) {
            $stream = $this->readStream($path);

            if (ftell($stream) !== 0) {
                rewind($stream);
            }
            fpassthru($stream);
            fclose($stream);
        });

        return $response;
    }
}