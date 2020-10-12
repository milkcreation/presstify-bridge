<?php declare(strict_types=1);

namespace tiFy\Field\Driver\FileJs;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use tiFy\Contracts\Field\{FieldDriver as FieldDriverContract, FileJs as FileJsContract};
use tiFy\Contracts\Routing\Route;
use tiFy\Field\FieldDriver;
use tiFy\Filesystem\StorageManager;
use tiFy\Support\Proxy\Router;

class FileJs extends FieldDriver implements FileJsContract
{
    /**
     * Url de traitement de requête XHR.
     * @var Route|string
     */
    protected $url = '';

    /**
     * @inheritDoc
     */
    public function boot(): void
    {
        $this->setUrl();
    }

    /**
     * {@inheritDoc}
     *
     * @return array {
     *      @var array $attrs Attributs HTML du champ.
     *      @var string $after Contenu placé après le champ.
     *      @var string $before Contenu placé avant le champ.
     *      @var string $name Clé d'indice de la valeur de soumission du champ.
     *      @var string $value Valeur courante de soumission du champ.
     *      @var array $viewer Liste des attributs de configuration du pilote d'affichage.
     *      @var string $dirname Chemin absolu vers le répertoire de stockage des fichiers téléchargés.
     *      @var boolean $multiple Activation du chargement de fichiers multiple.
     *      @var array $params Liste des paramètres complémentaires passées à la requête Xhr de téléchargement.
     *      @var array uploader Liste des paramètres de configuration du pilote JS de téléchargement.
     * }
     */
    public function defaults(): array
    {
        return [
            'attrs'     => [],
            'after'     => '',
            'before'    => '',
            'name'      => 'file',
            'value'     => '',
            'viewer'    => [],
            'container' => [],
            'dirname'   => PUBLIC_PATH,
            'multiple'  => true,
            'params'    => [],
            'uploader'  => [
                'driver' => 'dropzone'
            ]
        ];
    }

    /**
     * @inheritDoc
     */
    public function getUrl(...$params): string
    {
        return $this->url instanceof Route ? (string)$this->url->getUrl($params) : $this->url;
    }

    /**
     * @inheritDoc
     */
    public function parse(): FieldDriverContract
    {
        parent::parse();

        $this->set('attrs.data-control', 'file-js');

        $this->set('container.attrs.class', sprintf(
            $this->get('container.attrs.class') ?: '%s', 'FieldFileJs-container'
        ));
        $this->set('container.attrs.data-control', 'file-js.container');

        $uploader = $this->pull('uploader.driver', 'dropzone');
        if ($uploader === 'dropzone') {
            $this->parseDropzone();
        }
        $this->set('attrs.data-options', [$uploader => $this->pull('uploader', [])]);

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function parseDropzone(): FieldDriverContract
    {
        $this->set('uploader', array_merge([
            'createImageThumbnails' => false,
            'maxFiles'              => $this->get('multiple') ? null : 1,
            'params'                => array_merge($this->get('params', []), [
                '_dir' => $this->get('dirname')
            ]),
            'timeout'               => 0,
            'url'                   => $this->getUrl(),
        ], $this->get('uploader', [])));

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setUrl(?string $url = null): FieldDriverContract
    {
        $this->url = is_null($url) ? Router::xhr(md5($this->getAlias()), [$this, 'xhrResponse']) : $url;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function xhrResponse(...$args): array
    {
        $filesystem = (new StorageManager())->local(request()->input('_dir'));

        foreach (request()->files as $key => $f) {
            /** @var UploadedFile $f */
            $filesystem->put($f->getClientOriginalName(), file_get_contents($f->getPathname()));
        }

        return [
            'success' => true
        ];
    }
}