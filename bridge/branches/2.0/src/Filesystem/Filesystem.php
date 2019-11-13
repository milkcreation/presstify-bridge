<?php declare(strict_types=1);

namespace tiFy\Filesystem;

use League\Flysystem\{AdapterInterface, Cached\CachedAdapter, Filesystem as BaseFilesystem};
use Symfony\Component\HttpFoundation\StreamedResponse;
use tiFy\Contracts\Filesystem\Filesystem as FilesystemContract;
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