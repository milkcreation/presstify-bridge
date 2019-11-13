<?php declare(strict_types=1);

namespace tiFy\Asset;

use tiFy\Container\ServiceProvider;
use tiFy\Support\Locale;

class AssetServiceProvider extends ServiceProvider
{
    /**
     * Liste des noms de qualification des services fournis.
     * @internal requis. Tous les noms de qualification de services à traiter doivent être renseignés.
     * @var string[]
     */
    protected $provides = ['asset'];

    /**
     * @inheritDoc
     */
    public function register(): void
    {
        $this->getContainer()->share('asset', function () {
            $instance = new Asset($this->getContainer());

            $instance->setDataJs('base_url', $this->getContainer()->get('request')->getBaseUrl(), false);
            $instance->setDataJs('rewrite_base', $this->getContainer()->get('url')->rewriteBase(), false);
            $instance->setDataJs(
                'locale',
                ($locale = Locale::getLanguage()) ? $locale : ['language' => '', 'iso' => []]
            );

            return $instance;
        });
    }
}