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
     * Instance du controleur de gestion des paramètres
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

        $this->params = ParamsBag::createFromAttrs(array_merge($this->attributes, $attrs));

        $directory = $this->get('directory');
        parent::__construct($directory && is_dir($directory) ? $directory : null, $this->get('ext'));

        if ($override_dir = $this->get('override_dir')) {
            $this->setOverrideDir($override_dir);
        }
    }

    /**
     * @inheritdoc
     */
    public function all()
    {
        return $this->params->all();
    }

    /**
     * @inheritdoc
     */
    public function get($key, $default = '')
    {
        return $this->params->get($key, $default);
    }

    /**
     * @inheritdoc
     */
    public function getController($name)
    {
        $controller = $this->get('controller');

        return new $controller($this, $name);
    }

    /**
     * @inheritdoc
     */
    public function getOverrideDir($path = '')
    {
        return $this->folders->exists('_override')
            ? $this->folders->get('_override')->getPath() . ($path ? trim($path, '/') : '')
            : '';
    }

    /**
     * @inheritdoc
     */
    public function has($key)
    {
        return $this->params->has($key);
    }

    /**
     * @inheritdoc
     */
    public function make($name, $args = [])
    {
        $view = $this->getController($name);
        $view->data($args);

        return $view;
    }

    /**
     * @inheritdoc
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
     * @inheritdoc
     */
    public function set($key, $value)
    {
        switch($key) {
            case 'controller' :
                $this->setController($value);
                break;
            case 'directory' :
                $this->setDirectory($value);
                break;
            case 'ext' :
                $this->setFileExtension($value);
                break;
            case 'override_dir' :
                $this->setOverrideDir($value);
                break;
            default :
                $this->params->set($key, $value);
                break;
        }
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function setController($controller)
    {
        $this->params->set('controller', $controller);

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function setDirectory($directory)
    {
        $this->params->set('directory', $directory);

        return parent::setDirectory($directory);
    }

    /**
     * @inheritdoc
     */
    public function setFileExtension($fileExtension)
    {
        $this->params->set('ext', $fileExtension);

        return parent::setFileExtension($fileExtension);
    }

    /**
     * @inheritdoc
     */
    public function setOverrideDir($override_dir)
    {
        $this->params->set('override_dir', $override_dir);

        try {
            $this->addFolder('_override', $override_dir, true);
        } catch(LogicException $e) {
            if($this->getFolders()->get('_override')->getPath() !== $override_dir) :
                $this->modifyFolder('_override', $override_dir);
            endif;
        }
        return $this;
    }
}