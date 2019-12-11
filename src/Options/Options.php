<?php declare(strict_types=1);

namespace tiFy\Options;

use Psr\Container\ContainerInterface as Container;
use tiFy\Options\Page\OptionsPage;

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
     * @param Container $container Instance du conteneur d'injection de dépendances.
     *
     * @return void
     */
    public function __construct(Container $container)
    {
        $this->container = $container;

        add_action('init', function () {
            foreach(config('options', []) as $name => $attrs) {
                if ($attrs !== false) {
                    $this->items[$name] = new OptionsPage($name, $attrs);
                }
            }
            if (!isset($this->items['tify_options']) && !empty($config['tify_options'])) {
                $this->items['tify_options'] = new OptionsPage('tify_options', []);
            }
        });
    }
}