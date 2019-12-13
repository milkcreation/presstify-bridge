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
            $config = config('options', []);

            foreach($config as $name => $attrs) {
                if ($attrs !== false) {
                    $this->items[$name] = new OptionsPage($name, $attrs);
                }
            }

            if (!isset($this->items['tify_options'])) {
                if (!isset($config['tify_options']) || ($config['tify_options'] !== false)) {
                    $this->items['tify_options'] = new OptionsPage('tify_options', $config['tify_options'] ?? []);
                }
            }
        });
    }
}