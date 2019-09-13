<?php

namespace tiFy\Metabox\Tab;

use Closure;
use tiFy\Contracts\Metabox\MetaboxManager;
use tiFy\Contracts\Metabox\MetaboxFactory;
use tiFy\Wordpress\Contracts\WpScreen;
use tiFy\Kernel\Params\ParamsBag;

class MetaboxTabController extends ParamsBag
{
    /**
     * Liste des éléments à afficher.
     * @var MetaboxFactory[]
     */
    protected $items = [];

    /**
     * Instance de l'écran d'affichage courant.
     * @var WpScreen
     */
    protected $screen;

    /**
     * CONSTRUCTEUR.
     *
     * @param array $attrs Liste des attributs de configuration.
     * @param WpScreen $screen Liste des attributs de configuration.
     *
     * @return void
     */
    public function __construct($attrs = [], WpScreen $screen)
    {
        $this->screen = $screen;

        parent::__construct($attrs);

        /** @var MetaboxManager $metabox */
        $metabox = app()->get('metabox');

        $this->items = $metabox->collect();

        // Ordonnacement
        $max = $this->items->max(
            function (MetaboxFactory $item) {
                return $item->getPosition();
            }
        );
        if ($max) :
            $pad = 0;
            $this->items->each(
                function (MetaboxFactory $item, $key) use (&$pad, $max) {
                    $position = $item->getPosition() ?: ++$pad + $max;

                    return $item->set('position', absint($position));
                }
            );
        endif;

        $this->items = $this->items->filter(
            function (MetaboxFactory $item) {
                return $item->getContext() === 'tab' && $item->isActive();
            })
            ->sortBy(
                function (MetaboxFactory $item) {
                    return $item->getPosition();
                })
            ->all();

        if ($this->items) :
            switch ($this->screen->getObjectType()) :
                case 'post_type' :
                    if ($this->screen->getObjectName() === 'page') :
                        add_action('edit_page_form', [$this, 'render']);
                    else :
                        add_action('edit_form_advanced', [$this, 'render']);
                    endif;
                    break;
                case 'options' :
                    add_settings_section('navtab', null, [$this, 'render'], $this->screen->getObjectName());
                    break;
                case 'taxonomy' :
                    add_action($this->screen->getObjectName() . '_edit_form', [$this, 'render'], 10, 2);
                    break;
                case 'user' :
                    add_action('show_user_profile', [$this, 'render']);
                    add_action('edit_user_profile', [$this, 'render']);
                    break;
            endswitch;
        endif;
    }

    /**
     * Traitement de la liste des onglets de la boîte de saisie.
     *
     * @return array
     */
    protected function parseItems()
    {
        $items = [];

        /* @todo
         * $key_datas = ['name' => $item['name'], '_screen_id' => $this->screen->id];
         * $key = base64_encode(serialize($key_datas));
         * $current = ($this->current === $item['name']) ? true : false;
         *
         * data-key=\"{$key}\"
         */
        foreach ($this->items as $item) {
            $args = array_merge(func_get_args(), [$item->getArgs()]);

            $items[] = [
                'name'     => $item->getName(),
                'title'    => call_user_func_array([$item, 'getHeader'], $args),
                'parent'   => $item->getParent(),
                'content'  => call_user_func_array([$item, 'getContent'], $args),
                'args'     => $args,
                'position' => $item->getPosition(),
                // @todo 'current'   => (get_user_meta(get_current_user_id(), 'navtab' . get_current_screen()->id, true) === $node->getName())
            ];
        }
        return $items;
    }

    /**
     * Affichage.
     *
     * @return string
     */
    public function render()
    {
        $args = func_num_args() && ($this->screen->getObjectType() !== 'options') ? func_get_args() : [];

        $title = $this->get('title', __('Réglages', 'tify'));

        echo view()
            ->setDirectory(__DIR__ . '/views')
            ->render('display', [
                'title' => $title instanceof Closure ? call_user_func_array($title, $args) : $title,
                'items' => call_user_func_array([$this, 'parseItems'], $args)
            ]);
    }
}