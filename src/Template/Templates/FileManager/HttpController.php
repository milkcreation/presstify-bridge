<?php declare(strict_types=1);

namespace tiFy\Template\Templates\FileManager;

use Laminas\Diactoros\Response;
use League\Flysystem\FileNotFoundException;
use Symfony\Component\HttpFoundation\StreamedResponse;
use tiFy\Template\Factory\HttpController as BaseHttpController;
use tiFy\Template\Templates\FileManager\Contracts\HttpController as HttpControllerContract;

class HttpController extends BaseHttpController implements HttpControllerContract
{
    /**
     * Instance du gabarit d'affichage.
     * @var FileManager
     */
    protected $factory;

    /**
     * @inheritDoc
     */
    public function get()
    {
        $action = $this->factory->request()->input('action');
        $path = $this->factory->request()->input('path');
        $response = null;

        if (method_exists($this, $action)) {
            $response = $this->{$action}($path);
        }

        if (is_null($response)) {
            $response = new Response('php://memory', 405);
        }

        return $response;
    }

    /**
     * @inheritDoc
     */
    public function download(string $path): ?StreamedResponse
    {
        try {
            return $this->factory->filesystem()->download($path);
        } catch (FileNotFoundException $e) {
            return null;
        }
    }

    /**
     * @inheritDoc
     */
    public function preview(string $path): ?StreamedResponse
    {
        try {
            return $this->factory->filesystem()->response($path);
        } catch (FileNotFoundException $e) {
            return null;
        }
    }
}