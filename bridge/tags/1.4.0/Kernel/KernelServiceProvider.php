<?php

namespace tiFy\Kernel;

use tiFy\Container\ServiceProvider;
use tiFy\Kernel\Http\Request;
use tiFy\Kernel\Params\ParamsBag;
use tiFy\Kernel\Tools\File;
use tiFy\Kernel\Tools\Functions;
use tiFy\Kernel\Tools\Html;
use tiFy\Kernel\Tools\Str;

class KernelServiceProvider extends ServiceProvider
{
    /**
     * Liste des services fournis.
     * @var array
     */
    protected $provides = [
        'app',
        'params',
        'request',
        'tools',
        'tools.file',
        'tools.functions',
        'tools.html',
        'tools.str'
    ];

    /**
     * @inheritdoc
     */
    public function register()
    {
        $this->registerParams();
        $this->registerRequest();
        $this->registerTools();
    }

    /**
     * Déclaration du contrôleur de paramètres.
     *
     * @return void
     */
    public function registerParams()
    {
        $this->getContainer()->add('params', ParamsBag::class);
    }

    /**
     * Déclaration du contrôleur de requete HTTP.
     *
     * @return void
     */
    public function registerRequest()
    {
        $this->getContainer()->share('request', function () { return Request::capture(); });
    }

    /**
     * Déclaration des contrôleur des outils.
     *
     * @return void
     */
    public function registerTools()
    {
        $this->getContainer()->share('tools', Tools::class);
        $this->getContainer()->add('tools.file', File::class);
        $this->getContainer()->add('tools.functions', Functions::class);
        $this->getContainer()->add('tools.html', Html::class);
        $this->getContainer()->add('tools.str', Str::class);
    }
}