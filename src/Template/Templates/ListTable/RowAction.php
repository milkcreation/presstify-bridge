<?php declare(strict_types=1);

namespace tiFy\Template\Templates\ListTable;

use Closure;
use tiFy\Contracts\Routing\UrlFactory;
use tiFy\Support\{ParamsBag, Proxy\Partial};
use tiFy\Template\Factory\FactoryAwareTrait;
use tiFy\Template\Templates\ListTable\Contracts\{Item as ItemContract, RowAction as RowActionContract};
use tiFy\Support\Proxy\Url;

class RowAction extends ParamsBag implements RowActionContract
{
    use FactoryAwareTrait;

    /**
     * Instance du gabarit associé.
     * @var Factory
     */
    protected $factory;

    /**
     * Instance de l'élément associé.
     * @var ItemContract|null
     */
    protected $item;

    /**
     * Nom de qualification.
     * @var string
     */
    protected $name = '';

    /**
     * Instance de l'url.
     * @var UrlFactory
     */
    protected $url;

    /**
     * @inheritDoc
     */
    public function __toString(): string
    {
        return (string)$this->render();
    }

    /**
     * {@inheritDoc}
     *
     * @return array {
     *      @var string $content Contenu du lien (chaîne de caractère ou éléments HTML).
     *      @var array $attrs Liste des attributs complémentaires de la balise du lien.
     *      @var array $query_args Tableau associatif des arguments passés en requête dans l'url du lien.
     *      @var bool|string $nonce Activation de la création de l'identifiant de qualification de la clef de
     *                              sécurisation passée en requête dans l'url du lien|Identifiant de qualification
     *                              de la clef de sécurisation.
     *      @var bool|string $referer Activation de l'argument de l'url de référence passée en requête dans l'url du
     *                                lien.
     * }
     */
    public function defaults(): array
    {
        return [
            'attrs'      => [],
            'content'    => '',
            'url'        => '',
            'xhr'        => !!$this->factory->ajax()
        ];
    }

    /**
     * @inheritDoc
     */
    public function getBaseUrl(): string
    {
        return $this->isXhr() ? $this->factory->baseUrl() . '/xhr' : $this->factory->baseUrl();
    }

    /**
     * @inheritDoc
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @inheritDoc
     */
    public function isAvailable(): bool
    {
        return true;
    }

    /**
     * @inheritDoc
     */
    public function isXhr(): bool
    {
        return (bool)$this->get('xhr', !!$this->factory->ajax());
    }

    /**
     * @inheritDoc
     */
    public function parse(): RowActionContract
    {
        parent::parse();

        $this->parseUrl();
        $this->set('attrs.href', (string)$this->url);

        $default_class = "row-action row-action--" . $this->getName();
        if (!$this->has('attrs.class')) {
            $this->set('attrs.class', $default_class);
        } else {
            $this->set('attrs.class', sprintf($this->get('attrs.class', ''), $default_class));
        }

        if (!$this->get('attrs.class')) {
            $this->forget('attrs.class');
        }

        if (!$this->get('content')) {
            $this->set('content', $this->getName());
        }

        if ($this->isXhr()) {
            $this->set('attrs.data-control', 'list-table.row-action');
        }

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function parseUrl(): RowActionContract
    {
        if ($url = $this->pull('url')) {
            if (is_bool($url)) {
               $this->url = Url::set($this->getBaseUrl());
            } elseif (is_string($url)) {
                $this->url = Url::set($url);
            } elseif ($url instanceof Closure) {
                $this->url = $url($this->factory->item());
                return $this;
            } elseif (is_array($url)) {
                $this->url = Url::set($url['base'] ?? $this->getBaseUrl());

                if (isset($url['query_args'])) {
                    $query_args = $url['query_args'];

                    foreach($query_args as $key => $value) {
                        if ($value instanceof Closure) {
                            $this->url->with([$key => (string)$value($this->factory->item(), $this)]);
                        } elseif (is_string($value)) {
                            $this->url->with([$key => $value]);
                        }
                    }
                }

                if (isset($url['remove_query_args'])) {
                    $remove_query_args = $url['remove_query_args'];

                    if ($remove_query_args instanceof Closure) {
                        $this->url->without([(array)$remove_query_args($this->factory->item(), $this)]);
                    } elseif (is_array($remove_query_args)) {
                        $this->url->without($remove_query_args);
                    }
                }

                if (isset($url['redirect'])) {
                    $redirect = $url['redirect'];

                    if ($redirect instanceof Closure) {
                        $this->url->with(['redirect' => (string)$redirect($this->factory->item(), $this)]);
                    } elseif (is_string($redirect)) {
                        $this->url->with(['redirect' => $redirect]);
                    }
                }
            } elseif ($url instanceof UrlFactory) {
                $this->url = $url;
            }
        } else {
            $this->url = Url::set($this->getBaseUrl());
        }

        $this->url->with([
            'action' => $this->getName(),
            'id'     => $this->factory->item()->getKeyValue()
        ]);

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function render(): string
    {
        if ($this->get('hide_empty') && !$this->get('count_items', 0)) {
            return '';
        } else {
            return (string)Partial::get('tag', [
                'tag'       => 'a',
                'attrs'     => $this->get('attrs', []),
                'content'   => $this->get('content')
            ]);
        }
    }

    /**
     * @inheritDoc
     */
    public function setName(string $name): RowActionContract
    {
        $this->name = $name;

        return $this;
    }
}