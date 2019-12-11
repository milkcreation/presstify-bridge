<?php declare(strict_types=1);

namespace tiFy\View;

use League\Plates\Engine as LeaguePlatesEngine;
use LogicException;
use tiFy\Contracts\View\ViewEngine as ViewEngineContract;
use tiFy\Support\ParamsBag;

class ViewEngine extends LeaguePlatesEngine implements ViewEngineContract
{
    /**
     * Liste des attributs de configuration.
     * @var array {
     *      @var string $directory Chemin absolu vers le répertoire par défaut des gabarits.
     *      @var string $ext Extension des fichiers de gabarit.
     *      @var string $controller Controleur de gabarit.
     *      @var string $override_dir Chemin absolu vers le répertoire de surchage des gabarits.
     * }
     */
    protected $attributes = [
        'directory'     => null,
        'ext'           => 'php',
        'controller'    => ViewController::class,
        'override_dir'  => ''
    ];

    /**
     * Instance du gestionnaire de paramètres.
     * @var ParamsBag
     */
    protected $params;

    /**
     * CONSTRUCTEUR.
     *
     * @param string|array $attrs Liste des attributs de configuration
     *
     * @return void
     */
    public function __construct($attrs = [])
    {
        if (is_string($attrs)) {
            $attrs = ['directory'=> $attrs];
        }

        $this->params(array_merge($this->attributes, $attrs));

        $directory = $this->params('directory');
        parent::__construct($directory && is_dir($directory) ? $directory : null, $this->params('ext'));

        if ($override_dir = $this->params('override_dir')) {
            $this->setOverrideDir($override_dir);
        }
    }

    /**
     * @inheritDoc
     */
    public function getController($name)
    {
        $controller = $this->params('controller');

        return new $controller($this, $name);
    }

    /**
     * @inheritDoc
     */
    public function getOverrideDir($path = '')
    {
        return $this->folders->exists('_override')
            ? $this->folders->get('_override')->getPath() . ($path ? trim($path, '/') : '')
            : '';
    }

    /**
     * @inheritDoc
     */
    public function make($name, $args = [])
    {
        $view = $this->getController($name);
        $view->data($args);

        return $view;
    }

    /**
     * @inheritDoc
     */
    public function params($key = null, $default = null)
    {
        if (is_null($this->params)) {
            $this->params = new ParamsBag();
        }

        if (is_string($key)) {
            return $this->params->get($key, $default);
        } elseif (is_array($key)) {
            foreach ($key as $k => $v) {
                switch ($key) {
                    case 'controller' :
                        $this->setController($v);
                        break;
                    case 'directory' :
                        $this->setDirectory($v);
                        break;
                    case 'ext' :
                        $this->setFileExtension($v);
                        break;
                    case 'override_dir' :
                        $this->setOverrideDir($v);
                        break;
                    default :
                        $this->params->set([$k => $v]);
                        break;
                }
            }
        }

        return $this->params;
    }

    /**
     * @inheritDoc
     */
    public function modifyFolder($name, $directory, $fallback = null)
    {
        if ($folder = $this->getFolders()->get($name)) {
            if (is_null($folder)) {
                $fallback = $folder->getFallback();
            }
            $this->removeFolder($name)->addFolder($name, $directory, $fallback);
        }

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function share($key, $value = null): ViewEngineContract
    {
        $this->addData(is_array($key) ? $key : [$key => $value]);

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setController($controller)
    {
        $this->params(['controller' => $controller]);

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function setDirectory($directory)
    {
        $this->params(['directory' => $directory]);

        return parent::setDirectory($directory);
    }

    /**
     * @inheritDoc
     */
    public function setFileExtension($fileExtension)
    {
        $this->params(['ext' => $fileExtension]);

        return parent::setFileExtension($fileExtension);
    }

    /**
     * @inheritDoc
     */
    public function setOverrideDir($override_dir)
    {
        $this->params(['override_dir' => $override_dir]);

        try {
            $this->addFolder('_override', $override_dir, true);
        } catch(LogicException $e) {
            if($this->getFolders()->get('_override')->getPath() !== $override_dir) {
                $this->modifyFolder('_override', $override_dir);
            }
        }
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setParam($key, $value)
    {
        $this->params([$key => $value]);

        return $this;
    }
}