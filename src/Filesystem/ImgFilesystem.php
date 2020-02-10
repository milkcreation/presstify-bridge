<?php declare(strict_types=1);

namespace tiFy\Filesystem;

use Exception;
use tiFy\Contracts\Filesystem\ImgFilesystem as ImgFilesystemContract;
use tiFy\Support\MimeTypes;
use tiFy\Support\Proxy\Partial;

class ImgFilesystem extends LocalFilesystem implements ImgFilesystemContract
{
    /**
     * @inheritDoc
     */
    public function __invoke(string $path, array $attrs = []): ?string
    {
        return $this->render($path, $attrs);
    }

    /**
     * @inheritDoc
     */
    public function src(string $path): ?string
    {
        if ($this->has($path)) {
            try {
                $p = $this->path($path);
                $c = $this->read($path);

                if (MimeTypes::inType($p, ['svg', 'image'])) {
                    $m = mime_content_type($p);

                    return sprintf('data:%s;base64,%s', $m === 'image/svg' ? 'image/svg+xml' : $m, base64_encode($c));
                }
            } catch (Exception $e) {
                return null;
            }
        }

        return null;
    }

    /**
     * @inheritDoc
     */
    public function render(string $path, array $attrs = []): ?string
    {
        if ($this->has($path)) {
            try {
                $filename = $this->path($path);
                $content = $this->read($path);

                if (MimeTypes::inType($filename, 'svg')) {
                    return Partial::get('tag', [
                        'attrs'   => array_merge(['class' => ''], $attrs),
                        'content' => $content,
                        'tag'     => 'div',
                    ])->render();
                } elseif ($src = $this->src($path)) {
                    return Partial::get('tag', [
                        'attrs' => array_merge([
                            'alt'   => basename($filename),
                            'class' => ''
                        ], $attrs, ['src' => $src]),
                        'tag'   => 'img',
                    ])->render();
                } else {
                    return null;
                }
            } catch (Exception $e) {
                return null;
            }
        }

        return null;
    }
}