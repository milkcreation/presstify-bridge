<?php declare(strict_types=1);

namespace tiFy\Metabox;

use tiFy\Container\ServiceProvider;
use tiFy\Contracts\Metabox\{MetaboxContext as MetaboxContextContract,
    MetaboxDriver as MetaboxDriverContract,
    MetaboxScreen as MetaboxScreenContract,
    MetaboxView as MetaboxViewContract};
use tiFy\Metabox\Context\TabContext;
use tiFy\Metabox\Driver\{
    Color\Color as ColorDriver,
    CustomHeader\CustomHeader as CustomHeaderDriver,
    Excerpt\Excerpt as ExcerptDriver,
    Filefeed\Filefeed as FilefeedDriver,
    Icon\Icon as IconDriver,
    Imagefeed\Imagefeed as ImagefeedDriver,
    Order\Order as OrderDriver,
    Postfeed\Postfeed as PostfeedDriver,
    RelatedTerm\RelatedTerm as RelatedTermDriver,
    Slidefeed\Slidefeed as SlidefeedDriver,
    Subtitle\Subtitle as SubtitleDriver,
    Videofeed\Videofeed as VideofeedDriver
};

class MetaboxServiceProvider extends ServiceProvider
{
    /**
     * Liste des noms de qualification des services fournis.
     * {@internal Permet un chargement différé des services.}
     * @var string[]
     */
    protected $provides = [
        'metabox',
        MetaboxContextContract::class,
        MetaboxDriverContract::class,
        MetaboxScreenContract::class,
        MetaboxViewContract::class,
    ];

    /**
     * @inheritDoc
     */
    public function register(): void
    {
        $this->getContainer()->share('metabox', function () {
            return new MetaboxManager($this->getContainer());
        });

        $this->getContainer()->add(MetaboxContextContract::class, function () {
            return new MetaboxContext();
        });

        $this->getContainer()->add(MetaboxDriverContract::class, function () {
            return new MetaboxDriver();
        });

        $this->getContainer()->add(MetaboxScreenContract::class, function () {
            return new MetaboxScreen();
        });

        $this->registerContext();

        $this->registerDriver();
    }

    /**
     * @inheritDoc
     */
    public function registerContext(): void
    {
        $this->getContainer()->add("metabox.context.tab", function () {
            return new TabContext();
        });
    }

    /**
     * @inheritDoc
     */
    public function registerDriver(): void
    {
        $this->getContainer()->add('metabox.driver.color', function () {
            return new ColorDriver();
        });

        $this->getContainer()->add("metabox.driver.custom-header", function () {
            return new CustomHeaderDriver();
        });

        $this->getContainer()->add("metabox.driver.excerpt", function () {
            return new ExcerptDriver();
        });

        $this->getContainer()->add("metabox.driver.filefeed", function () {
            return new FilefeedDriver();
        });

        $this->getContainer()->add("metabox.driver.icon", function () {
            return new IconDriver();
        });

        $this->getContainer()->add("metabox.driver.order", function () {
            return new OrderDriver();
        });

        $this->getContainer()->add("metabox.driver.imagefeed", function () {
            return new ImagefeedDriver();
        });

        $this->getContainer()->add("metabox.driver.postfeed", function () {
            return new PostfeedDriver();
        });

        $this->getContainer()->add("metabox.driver.related-term", function () {
            return new RelatedTermDriver();
        });

        $this->getContainer()->add("metabox.driver.slidefeed", function () {
            return new SlidefeedDriver();
        });

        $this->getContainer()->add("metabox.driver.subtitle", function () {
            return new SubtitleDriver();
        });

        $this->getContainer()->add("metabox.driver.videofeed", function () {
            return new VideofeedDriver();
        });
    }
}