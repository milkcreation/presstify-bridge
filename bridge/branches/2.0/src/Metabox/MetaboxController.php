<?php declare(strict_types=1);

namespace tiFy\Metabox;

use tiFy\Contracts\Metabox\MetaboxController as MetaboxControllerContract;
use tiFy\Contracts\Metabox\MetaboxFactory;
use tiFy\Contracts\View\ViewEngine;
use tiFy\Support\ParamsBag;
use WP_Screen;

abstract class MetaboxController extends ParamsBag implements MetaboxControllerContract
{
    /**
     * Instance de l'élément.
     * @var MetaboxFactory|null
     */
    protected $item;

    /**
     * Instance du moteur de gabarits d'affichage.
     * @var ViewEngine
     */
    protected $viewer;

    /**
     * @inheritDoc
     */
    public function boot() {}

    /**
     * @inheritDoc
     */
    public function content($var1 = null, $var2 = null, $var3 = null)
    {
        return __('Aucun contenu à afficher', 'tify');
    }

    /**
     * @inheritDoc
     */
    public function getObjectName()
    {
        return $this->item->getScreen()->getObjectName();
    }

    /**
     * @inheritDoc
     */
    public function getObjectType()
    {
        return $this->item->getScreen()->getObjectType();
    }

    /**
     * @inheritDoc
     */
    public function header($var1 = null, $var2 = null, $var3 = null)
    {
        return $this->item->getTitle();
    }

    /**
     * @inheritDoc
     */
    public function load(WP_Screen $wp_screen)
    {

    }

    /**
     * @inheritDoc
     */
    public function parse(): MetaboxControllerContract
    {
        parent::parse();

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function prepare(): MetaboxControllerContract
    {
        add_action('current_screen', function (WP_Screen $wp_screen) {
            if ($wp_screen->id === $this->item->getScreen()->getHookname()) {
                $this->load($wp_screen);
            }
        });

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setFactory(MetaboxFactory $factory): MetaboxControllerContract
    {
        $this->item = $factory;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function viewer($view = null, $data = [])
    {
        if (!$this->viewer) {
            $cinfo = class_info($this);
            $default_dir = $cinfo->getDirname() . '/views';
            $this->viewer = view()
                ->setDirectory(is_dir($default_dir) ? $default_dir : null)
                ->setController(MetaboxView::class)
                ->setOverrideDir(
                    (($override_dir = $this->get('viewer.override_dir')) && is_dir($override_dir))
                        ? $override_dir
                        : (is_dir($default_dir) ? $default_dir : $cinfo->getDirname())
                )
                ->set('metabox', $this);
        }

        if (func_num_args() === 0) {
            return $this->viewer;
        }

        return $this->viewer->make("_override::{$view}", $data);
    }
}