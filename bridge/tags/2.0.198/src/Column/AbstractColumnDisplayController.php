<?php

namespace tiFy\Column;

use tiFy\Contracts\Column\ColumnDisplayInterface;
use tiFy\Contracts\View\ViewEngine;

abstract class AbstractColumnDisplayController implements ColumnDisplayInterface
{
    /**
     * Instance de l'élément.
     * @var ColumnItemController
     */
    protected $item;

    /**
     * Instance du moteur de gabarits d'affichage.
     * @return ViewEngine
     */
    protected $viewer;

    /**
     * CONSTRUCTEUR.
     *
     * @return void
     */
    public function __construct(ColumnItemController $item)
    {
        $this->item = $item;

        add_action(
            'current_screen',
            function ($wp_current_screen) {
                if ($wp_current_screen->id === $this->item->getScreen()->getHookname()) :
                    $this->load($wp_current_screen);
                endif;
            }
        );

        $this->boot();
    }

    /**
     * Récupération de l'affichage depuis l'instance.
     *
     * @return string
     */
    public function __invoke()
    {
        return call_user_func_array([$this, 'content'], func_get_args());
    }

    /**
     * {@inheritdoc}
     */
    public function boot()
    {

    }

    /**
     * {@inheritdoc}
     */
    public function content($var1, $var2, $var3 = null)
    {
        return __('Pas de contenu à afficher', 'tify');
    }

    /**
     * {@inheritdoc}
     */
    public function header()
    {
        return $this->item->getTitle() ? : $this->item->getName();
    }

    /**
     * {@inheritdoc}
     */
    public function load($wp_screen)
    {

    }

    /**
     * {@inheritdoc}
     */
    public function viewer($view = null, $data = [])
    {
        if (!$this->viewer) :
            $cinfo = class_info($this);
            $default_dir = $cinfo->getDirname() . '/views';
            $this->viewer = view()
                ->setDirectory(is_dir($default_dir) ? $default_dir : null)
                ->setController(ColumnView::class)
                ->setOverrideDir(
                    (($override_dir = $this->get('viewer.override_dir')) && is_dir($override_dir))
                        ? $override_dir
                        : (is_dir($default_dir) ? $default_dir : $cinfo->getDirname())
                )
                ->set('column', $this);
        endif;

        if (func_num_args() === 0) :
            return $this->viewer;
        endif;

        return $this->viewer->make("_override::{$view}", $data);
    }
}