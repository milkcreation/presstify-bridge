<?php declare(strict_types=1);

namespace tiFy\PostType;

use tiFy\Container\ServiceProvider;

class PostTypeServiceProvider extends ServiceProvider
{
    /**
     * Liste des noms de qualification des services fournis.
     * @internal requis. Tous les noms de qualification de services à traiter doivent être renseignés.
     * @var string[]
     */
    protected $provides = [
        'post-type',
        'post-type.meta'
    ];

    /**
     * @inheritDoc
     */
    public function register(): void
    {
        $this->registerManager();
        $this->registerPostMeta();
    }

    /**
     * Déclaration du controleur de gestion des types de contenu.
     *
     * @return void
     */
    public function registerManager(): void
    {
        $this->getContainer()->share('post-type', function() {
            return new PostType($this->getContainer());
        });
    }

    /**
     * Déclaration du controleur de gestion des metadonnées de post.
     *
     * @return void
     */
    public function registerPostMeta(): void
    {
        $this->getContainer()->share('post-type.meta', function () {
            return new PostTypePostMeta();
        });
    }
}