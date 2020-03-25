<?php declare(strict_types=1);

namespace tiFy\Wordpress\Option;

use tiFy\Wordpress\Contracts\Option\{Option as OptionContract, OptionPage as OptionPageContract};
use tiFy\Support\ParamsBag;

class Option implements OptionContract
{
    /**
     * Liste des pages de réglages des options déclarées.
     * @var OptionPageContract[]|array
     */
    protected $pages = [];

    /**
     * CONSTRUCTEUR.
     *
     * @return void
     */
    public function __construct()
    {
        add_action('init', function () {
            foreach (config('options', []) as $name => $attrs) {
                if ($attrs !== false) {
                    $this->registerPage($name, $attrs);
                }
            }

            if (!$this->getPage('tify_options')) {
                $params = (new ParamsBag())->set(config('options.tify_options', []));
                if (!$params->get('title')) {
                    $params->set('title', __('Options du site', 'theme'));
                }

                if (!$params->has('admin_bar')) {
                    $params->set('admin_bar', true);
                }

                $this->registerPage('tify_options', $params->all());
            }
        });
    }

    /**
     * @inheritDoc
     */
    public function getPage(string $name): ?OptionPageContract
    {
        return $this->pages[$name] ?? null;
    }

    /**
     * @inheritDoc
     */
    public function registerPage(string $name, $attrs = []): ?OptionPageContract
    {
        $page = $attrs instanceof OptionPageContract
            ? $attrs : (is_array($attrs) ? (new OptionPage())->set($attrs) : null);

        if ($page instanceof OptionPageContract) {
            $page->setManager($this)->setName($name)->boot();

            return $this->pages[$name] = $page->parse();
        } else {
            return null;
        }
    }
}