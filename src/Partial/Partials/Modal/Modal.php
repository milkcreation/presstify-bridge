<?php declare(strict_types=1);

namespace tiFy\Partial\Partials\Modal;

use Closure;
use Illuminate\Support\Arr;
use tiFy\Contracts\Partial\{Modal as ModalContract, PartialFactory as PartialFactoryContract};
use tiFy\Partial\PartialFactory;

class Modal extends PartialFactory implements ModalContract
{
    /**
     * {@inheritDoc}
     *
     * @return array {
     *      @var array $attrs Attributs HTML du champ.
     *      @var string $after Contenu placé après le champ.
     *      @var string $before Contenu placé avant le champ.
     *      @var array $viewer Liste des attributs de configuration du pilote d'affichage.
     *      @var array $options {
     *          Liste des options d'affichage.
     *      }
     *      @var bool $animation Activation de l'animation.
     *      @var string $size Taille d'affichage de la fenêtre de dialogue lg|sm|full|flex.
     *      @var bool|string|callable $backdrop_close_button Affichage d'un bouton fermeture externe. Chaine de
     *                                                      caractère à afficher ou booléen pour activer désactiver ou
     *                                                      fonction/méthode d'affichage.
     *      @var bool|string|callable $header Affichage de l'entête de la fenêtre. Chaine de caractère à afficher ou
     *                                        booléen pour activer désactiver ou fonction/méthode d'affichage.
     *      @var bool|string|callable $body Affichage du corps de la fenêtre. Chaine de caractère à afficher ou booléen
     *                                      pour activer désactiver ou fonction/méthode d'affichage.
     *      @var bool|string|callable $footer Affichage d'un bouton fermeture externe. Chaine de caractère à afficher ou
     *                                        booléen pour activer désactiver ou fonction/méthode d'affichage.
     *      @var bool|string|array $ajax Activation du chargement du contenu Ajax ou Contenu a charger ou liste des
     *                                   attributs de récupération Ajax
     * }
     */
    public function defaults(): array
    {
        return [
            'attrs'         => [],
            'after'         => '',
            'before'        => '',
            'viewer'        => [],
            'options'        => [],
            'animation'      => true,
            'size'           => '',
            'backdrop_close' => true,
            'header'         => true,
            'body'           => true,
            'footer'         => true,
            'ajax'           => false,
        ];
    }

    /**
     * @inheritdoc
     */
    public function parse(): PartialFactoryContract
    {
        parent::parse();

        $class = 'modal';
        if ($this->get('animation')) {
            $class .= ' fade';
        }
        if (!$this->get('attrs.id')) {
            $this->set('attrs.id', 'Modal-' . $this->getId());
        }

        $this->set('attrs.class', $this->get('attrs.class', '') . " {$class}");

        $this->set('attrs.role', 'dialog');
        $this->set('attrs.data-control', 'modal');

        $this->set('options', array_merge([
            'backdrop' => true,
            'keyboard' => true,
            'focus'    => true,
            'show'     => true
        ], $this->get('options')));

        foreach (['backdrop', 'keyboard', 'focus', 'show'] as $key) {
            switch($key) {
                case 'backdrop' :
                    $value = $this->get("options.backdrop");
                    $value = ($value === 'static') ? 'static': ($value ? 'true' : 'false');
                    break;
                default :
                    $value = $this->get("options.{$key}") ? 'true' : 'false';
                    break;
            }
            $this->set("attrs.data-{$key}", $value);
        }

        if ($backdrop_close = $this->get('backdrop_close')) {
            $backdrop_close = $backdrop_close instanceof Closure
                ? call_user_func($backdrop_close, $this->all())
                : (is_string($backdrop_close) ? $backdrop_close : $this->viewer('backdrop_close', $this->all()));
            $this->set('backdrop_close', $backdrop_close);
        }

        if ($body = $this->get('body')) {
            $body = $body instanceof Closure
                ? call_user_func($body, $this->all())
                : (is_string($body) ? $body : $this->viewer('body', $this->all()));
            $this->set('body', $body);
        }

        if ($footer = $this->get('footer')) {
            $footer = $footer instanceof Closure
                ? call_user_func($footer, $this->all())
                : (is_string($footer) ? $footer : $this->viewer('footer', $this->all()));
            $this->set('footer', $footer);
        }

        if ($header = $this->get('header')) {
            $header = $header instanceof Closure
                ? call_user_func($this->get('header'), $this->all())
                : (is_string($header) ? $header : $this->viewer('header', $this->all()));
            $this->set('header', $header);
        }

        $this->set('size', in_array($this->get('size'), ['lg', 'sm', 'full', 'flex'])
            ? 'modal-' . $this->get('size') : '');

        $this->set('attrs.data-options.id', $this->getId());

        $ajax = $this->get('ajax', false);

        if (is_string($ajax)) {
            asset()->setDataJs($this->getId(), ['content' => $ajax], true);
        }

        $this->set('attrs.data-options.ajax',
            (
            $ajax !== false
                ? array_merge(
                is_array($ajax) ? $ajax : [],
                [
                    'action' => 'partial_modal',
                    'csrf'   => wp_create_nonce('PartialModal' . $this->getId()),
                    'data'   => []
                ]
            )
                : false
            )
        );

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function trigger($attrs = []): string
    {
        $attrs = array_merge([
            'tag'     => 'a',
            'attrs'   => [],
            'content' => ''
        ], $attrs);

        if ((Arr::get($attrs, 'tag') === 'a') && !Arr::has($attrs, 'attrs.href')) {
            Arr::set($attrs, 'attrs.href', "#{$this->get('attrs.id')}");
        }

        Arr::set($attrs, 'attrs.data-control', 'modal-trigger');
        Arr::set($attrs, 'attrs.data-target', "#{$this->get('attrs.id')}");

        return (string)$this->viewer('trigger', $attrs);
    }

    /**
     * @inheritdoc
     */
    public function xhrGetContent()
    {
        return [
            'success' => true,
            'html'    => (string)$this->viewer('ajax')
        ];
    }
}