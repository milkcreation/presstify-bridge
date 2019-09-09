<?php declare(strict_types=1);

namespace tiFy\Template\Templates\FileManager;

use League\Route\Http\Exception\MethodNotAllowedException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use tiFy\Template\Factory\FactoryHttpXhrController;
use tiFy\Template\Templates\FileManager\Contracts\HttpXhrController as HttpXhrControllerContract;

class HttpXhrController extends FactoryHttpXhrController implements HttpXhrControllerContract
{
    /**
     * Instance du gabarit d'affichage.
     * @var FileManager
     */
    protected $factory;

    /**
     * @inheritDoc
     */
    public function post()
    {
        $action = $this->factory->request()->input('action');
        $path = rawurldecode($this->factory->request()->input('path'));
        $response = null;

        if (method_exists($this, $action)) {
            $response = $this->{$action}($path);
        }

        if (is_null($response)) {
            throw new MethodNotAllowedException();
        }

        return $response;
    }

    /**
     * @inheritDoc
     */
    public function browse(string $path): array
    {
        $this->factory->setPath($path);

        return [
            'success' => true,
            'views'   => [
                'files' => (string)$this->factory->viewer(
                    'browser-items', ['files' => $this->factory->getFiles()]
                )
            ]
        ];
    }

    /**
     * @inheritDoc
     */
    public function create(string $path): array
    {
        $file = $this->factory->getFile($path);
        $name = request()->input('name');

        if (!validator()::notEmpty()->validate($name)) {
            return [
                'success' => false,
                'views'   => [
                    'notice' => (string)$this->notice(__('Le nom du dossier ne peut être vide.', 'tify'), 'warning')
                ]
            ];
        }

        $root = $file->isDir() ? $path : $file->getDirname();
        $path = "{$root}/{$name}";

        if ($this->factory->filesystem()->has($path)) {
            return [
                'success' => false,
                'views'   => [
                    'notice' => (string)$this->notice(
                        __('Un dossier portant ce nom autre existe déjà dans le répertoire courant.', 'tify'),
                        'warning'
                    )
                ]
            ];
        } elseif ($this->factory->filesystem()->createDir($path)) {
            $this->factory->setPath($root);

            return [
                'success' => true,
                'views'   => [
                    'breadcrumb' => (string)$this->factory->breadcrumb(),
                    'content'    => (string)$this->factory->getFiles(),
                    'sidebar'    => (string)$this->factory->sidebar(),
                    'notice'     => (string)$this->notice(__('Le dossier a été créé avec succès.', 'tify'), 'success')
                ]
            ];
        } else {
            return [
                'success' => false,
                'views'   => [
                    'notice' => (string)$this->notice(
                        __('ERREUR SYSTEME : Impossible de créer le dossier.', 'tify'), 'error')
                ]
            ];
        }
    }

    /**
     * @inheritDoc
     */
    public function delete(string $path): array
    {
        if (!$this->factory->filesystem()->has($path)) {
            return [
                'success' => false,
                'views'   => [
                    'notice' => $this->notice(__('Impossible de trouver l\'élément à supprimer.', 'tify'), 'warning')
                ]
            ];
        } else {
            $this->factory->setPath($path);
            $file = $this->factory->getFile($path);

            if ($file->isDir()) {
                $this->factory->adapter()->deleteDir($path);
            } else {
                $this->factory->adapter()->delete($path);
            }

            $this->factory->setPath($file->getDirname());

            return [
                'success' => true,
                'views'   => [
                    'breadcrumb' => (string)$this->factory->breadcrumb(),
                    'content'    => (string)$this->factory->getFiles(),
                    'sidebar'    => (string)$this->factory->sidebar(),
                    'notice'     => $this->notice(__('L\'élément a été supprimé avec succès.', 'tify'), 'success')
                ]
            ];
        }
    }

    /**
     * @inheritDoc
     */
    public function get(string $path): array
    {
        if ($path && ($path !== '/') && !$this->factory->filesystem()->has($path)) {
            return [
                'success' => false,
                'views'   => [
                    'notice' => $this->notice(__('Impossible de trouver l\'élément.' . $path, 'tify'), 'warning')
                ]
            ];
        } else {
            $this->factory->setPath($path);
            $file = $this->factory->getFile($path);

            if ($file->isDir()) {
                return [
                    'success' => true,
                    'views'   => [
                        'breadcrumb' => (string)$this->factory->breadcrumb(),
                        'content'    => (string)$this->factory->getFiles(),
                        'sidebar'    => (string)$this->factory->sidebar()
                    ]
                ];
            } else {
                return [
                    'success' => true,
                    'views'   => [
                        'sidebar' => (string)$this->factory->sidebar()
                    ]
                ];
            }
        }
    }

    /**
     * @inheritDoc
     */
    public function notice($message, $type = 'info', $attrs = []): string
    {
        return (string)partial('notice', array_merge([
            'attrs'   => [
                'class' => 'FileManager-noticeMessage FileManager-noticeMessage--'. $type
            ],
            'content' => $message,
            'dismiss' => true,
            'timeout' => 2000,
            'type'    => $type
        ], $attrs));
    }

    /**
     * @inheritDoc
     */
    public function rename(string $path): array
    {
        $name = request()->input('name');

        if (!$this->factory->filesystem()->has($path)) {
            return [
                'success' => false,
                'views'   => [
                    'notice' => $this->notice(__('Impossible de trouver l\'élément à renommer.', 'tify'), 'warning')
                ]
            ];
        } elseif (!validator()::notEmpty()->validate($name)) {
            return [
                'success' => false,
                'views'   => [
                    'notice'    => $this->notice(__('Le nom ne peut être vide.'), 'warning')
                ]
            ];
        }

        $this->factory->setPath($path);
        $file = $this->factory->getFile($path);
        $newpath = $this->factory->getFile()->getDirname() . '/' . $name;
        $newpath .= ($file->isFile() && (request()->input('keep') === 'on') && $file->getExtension())
            ? '.' . $file->getExtension()
            : '';

        if ($this->factory->filesystem()->has($newpath)) {
            return [
                'success' => false,
                'views'   => [
                    'notice'    => $this->notice(__('Un autre élément porte déjà ce nom.', 'tify'), 'warning')
                ]
            ];
        } elseif ($this->factory->adapter()->rename($path, $newpath)) {
            $this->factory->setPath($newpath);

            return [
                'success' => true,
                'views'   => [
                    'breadcrumb' => (string)$this->factory->breadcrumb(),
                    'content'    => (string)$this->factory->getFiles(),
                    'sidebar'    => (string)$this->factory->sidebar(),
                    'notice'    => $this->notice(__('L\'élément a bien été renommé.', 'tify'), 'success')
                ]
            ];
        } else {
            return [
                'success' => false,
                'views'   => [
                    'notice' => (string)$this->notice(
                        __('ERREUR SYSTEME : Impossible de renommer l\'élément.', 'tify'), 'error')
                ]
            ];
        }
    }

    /**
     * @inheritDoc
     */
    public function upload(string $path): array
    {
        $this->factory->setPath($path);
        $file = $this->factory->getFile($path);

        $path = $file->isDir() ? $path : $file->getDirname();

        foreach (request()->files as $key => $f) {
            /* @var UploadedFile $f */
            $this->factory->filesystem()->put(
                $path . '/' . $f->getClientOriginalName(),
                file_get_contents($f->getPathname())
            );
        }

        return [
            'success' => true,
            'views'   => [
                'breadcrumb' => (string)$this->factory->breadcrumb(),
                'content'    => (string)$this->factory->getFiles()
            ]
        ];
    }
}