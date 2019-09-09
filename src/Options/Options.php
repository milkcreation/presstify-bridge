<?php declare(strict_types=1);

namespace tiFy\Options;

use Psr\Container\ContainerInterface as Container;
use tiFy\Options\Page\OptionsPage;
use tiFy\Support\Manager;

class Options
{
    /**
     * Instance de conteneur d'injection de dépendances.
     * @var Container
     */
    protected $container;

    /**
     * Liste des éléments.
     * @var OptionsPage[]
     */
    protected $items = [];

    /**
     * CONSTRUCTEUR.
     *
     * @return void
     */
    public function __construct(Container $container)
    {
        $this->container = $container;

        add_action('init', function () {
            foreach(config('options', []) as $name => $attrs) {
                $this->items[$name] = new OptionsPage($name, $attrs);
            }
            if (!isset($this->items['tify_options'])) {
                $this->items['tify_options'] = new OptionsPage('tify_options', []);
            }
        });
    }
}