<?php declare(strict_types=1);

namespace tiFy\Partial\Driver\Modal;

use Closure;
use tiFy\Contracts\Partial\{Modal as ModalContract, PartialDriver as PartialDriverContract};
use tiFy\Contracts\Routing\Route;
use tiFy\Partial\PartialDriver;
use tiFy\Support\{ParamsBag, Proxy\Request, Proxy\Router};

class Modal extends PartialDriver implements ModalContract
{
    /**
     * Url de traitement de requête XHR.
     * @var Route|string
     */
    protected $url = '';

    /**
     * @inheritDoc
     */
    public function boot(): void
    {
        parent::boot();
        $this->setUrl();
    }

    /**
     * {@inheritDoc}
     *
     * @return array {
     * @var array $attrs Attributs HTML du champ.
     * @var string $after Contenu placé après le champ.
     * @var string $before Contenu placé avant le champ.
     * @var array $viewer Liste des attributs de configuration du pilote d'affichage.
     * @var bool|string|array $ajax Activation du chargement du contenu Ajax ou Contenu a charger ou liste des
     *                                   attributs de récupération Ajax.
     * @var bool $ajax_cacheable Conservation en cache du contenu récupéré via Ajax.
     * @var bool|string $animated Activation de l'animation.
     * @var bool|array $backdrop .
     * @var bool|string|array|callable $content {
     * @var bool|string|callable $header Affichage de l'entête de la fenêtre. Chaine de caractère à afficher ou
     *                                        booléen pour activer désactiver ou fonction/méthode d'affichage.
     * @var bool|string|callable $body Affichage du corps de la fenêtre. Chaine de caractère à afficher ou booléen
     *                                      pour activer désactiver ou fonction/méthode d'affichage.
     * @var bool|string|callable $footer Affichage d'un bouton fermeture externe. Chaine de caractère à afficher ou
     *                                        booléen pour activer désactiver ou fonction/méthode d'affichage.
     *      }
     * @var array $options Liste des options d'affichage.
     * @var string $size Taille d'affichage de la fenêtre de dialogue sm|lg|full|flex.
     * }
     */
    public function defaults(): array
    {
        return [
            'attrs'          => [],
            'after'          => '',
            'before'         => '',
            'viewer'         => [],
            'ajax'           => false,
            'ajax_cacheable' => true,
            'animated'       => true,
            'backdrop'       => [
                'close' => true,
            ],
            'classes'        => [],
            'close'          => true,
            'content'        => [
                'body'    => true,
                'header'  => true,
                'footer'  => true,
                'spinner' => true,
            ],
            'options'        => [],
            'size'           => '',
        ];
    }

    /**
     * @inheritDoc
     */
    public function getUrl(...$params): string
    {
        return $this->url instanceof Route ? $this->url->getUrl($params) : $this->url;
    }

    /**
     * @inheritDoc
     */
    public function parse(): PartialDriverContract
    {
        parent::parse();

        $defaultClasses = [
            'bkclose' => 'modal-backdrop-close ThemeButton--close',
            'body'    => 'modal-body',
            'close'   => 'modal-close ThemeButton--close',
            'content' => 'modal-content',
            'dialog'  => 'modal-dialog',
            'footer'  => 'modal-footer',
            'header'  => 'modal-header',
            'spinner' => 'modal-spinner',
        ];
        foreach ($defaultClasses as $k => $v) {
            $this->set(["classes.{$k}" => sprintf($this->get("classes.{$k}", '%s'), $v)]);
        }

        $this->set([
            'attrs.data-control' => $this->get('attrs.data-control', 'modal'),
            'attrs.data-id'      => $this->get('attrs.id', $this->getId()),
            'size'               => in_array($this->get('size'), ['sm', 'lg', 'full', 'flex'])
                ? 'modal-' . $this->get('size') : '',
        ]);

        $options = array_merge([
            'backdrop' => true,
            'keyboard' => true,
            'focus'    => true,
            'show'     => true,
        ], $this->pull('options'));

        foreach (['backdrop', 'keyboard', 'focus', 'show'] as $key) {
            switch ($key) {
                case 'backdrop' :
                    $value = ($options[$key] === 'static') ? 'static' : ($options[$key] ? 'true' : 'false');
                    break;
                default :
                    $value = $options[$key] ? 'true' : 'false';
                    break;
            }
            $this->set("attrs.data-{$key}", $value);
        }

        if ($bkClose = $this->get('backdrop.close', true)) {
            if ($bkClose instanceof Closure) {
                $this->set('backdrop.close', (string)$bkClose($this->all()));
            } elseif (is_string($bkClose)) {
                $this->set('backdrop.close', $bkClose);
            } else {
                $this->set('backdrop.close', (string)$this->viewer('backdrop-close', $this->all()));
            }
        }

        if ($close = $this->get('close', true)) {
            if ($close instanceof Closure) {
                $this->set('close', (string)$close($this->all()));
            } elseif (is_string($close)) {
                $this->set('close', $close);
            } else {
                $this->set('close', (string)$this->viewer('close', $this->all()));
            }
        }

        if ($content = $this->get('content')) {
            if ($content instanceof Closure) {
                $this->set('content', (string)$content($this->all()));
            } elseif (is_string($content)) {
                $this->set('content', $content);
            } else {
                foreach (['body', 'footer', 'header', 'spinner'] as $item) {
                    if (${$item} = $this->get("content.{$item}", true)) {
                        if (${$item} instanceof Closure) {
                            $this->set("content.{$item}", (string)${$item}($this->all()));
                        } elseif (is_string(${$item})) {
                            $this->set("content.{$item}", ${$item});
                        } else {
                            $this->set("content.{$item}", (string)$this->viewer("content-{$item}", $this->all()));
                        }
                    }
                }
            }
        } else {
            $this->get('content', (string)$this->viewer('content', $this->all()));
        }

        $this->set([
            'attrs.data-options' => [
                'animated'       => $this->get('animated'),
                'backdrop-close' => !!$this->get('backdrop.close'),
                'body'           => !!$this->get('content.body'),
                'classes'        => $this->get('classes', []),
                'close'          => !!$this->get('close'),
                'footer'         => !!$this->get('content.footer'),
                'header'         => !!$this->get('content.header'),
                'spinner'        => !!$this->get('content.spinner'),
                'size'           => $this->get('size'),
            ],
        ]);

        if ($ajax = $this->get('ajax', false)) {
            $defaultAjax = [
                'data'     => [],
                'dataType' => 'json',
                'method'   => 'post',
                'url'      => $this->getUrl(),
            ];

            $this->set([
                'attrs.data-options.ajax' => is_array($ajax) ? array_merge($defaultAjax, $ajax) : $defaultAjax,
                'attrs.data-options.ajax_cacheable' => !!$this->get('ajax_cacheable')
            ]);

            if (!$this->get('attrs.data-options.ajax.data.viewer')) {
                $this->set('attrs.data-options.ajax.data.viewer', $this->get('viewer', []));
            }
        }

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setUrl(?string $url = null): ModalContract
    {
        $this->url = is_null($url) ? Router::xhr(md5($this->getAlias()), [$this, 'xhrResponse']) : $url;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function trigger($attrs = []): string
    {
        $params = (new ParamsBag())->set(array_merge([
            'tag'     => 'a',
            'attrs'   => [],
            'content' => '',
            // Liste des options passées à la modale
            'options' => [],
        ], $attrs));

        if (($params->get('tag') === 'a') && !$params->has('attrs.href')) {
            $params->set('attrs.href', "#{$this->get('attrs.data-id')}");
        }

        $params->set([
            'attrs.data-control' => 'modal.trigger',
            'attrs.data-target'  => "{$this->get('attrs.data-id')}",
        ]);

        if ($options = $params->pull('options')) {
            $params->set('attrs.data-options', $options);
        }

        return $this->viewer('trigger', $params->all());
    }

    /**
     * @inheritdoc
     */
    public function xhrResponse(...$args): array
    {
        $this->set('viewer', Request::input('viewer', []))->parseViewer();

        return [
            'success' => true,
            'data'    => $this->viewer('ajax-content'),
        ];
    }
}