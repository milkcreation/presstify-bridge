<?php

namespace tiFy\Column;

use tiFy\Contracts\Column\ColumnDisplayInterface;
use tiFy\Contracts\Column\ColumnItem as ColumnItemContract;
use tiFy\Kernel\Params\ParamsBag;
use tiFy\Wordpress\Contracts\WpScreen as WpScreenContract;
use tiFy\Wordpress\Routing\WpScreen;
use WP_Screen;

class ColumnItemController extends ParamsBag implements ColumnItemContract
{
    /**
     * Compteur d'indices de qualification.
     * @var int
     */
    protected static $_index = 0;

    /**
     * Indicateur d'activation.
     * @var boolean
     */
    protected $active = false;

    /**
     * Traitement des arguments de configuration
     *
     * @var array $attrs {
     *      Attributs de configuration
     *
     *      @var string $name Nom de qualification de la colonne.
     *      @var int $position Position de la colonne.
     *      @var string $title Intitulé de qualification.
     *      @var string|callable|ColumnDisplayInterface $content
     * }
     *
     * @return array
     */
    protected $attributes = [
        'position' => 0,
        'title'    => '',
        'content'  => ''
    ];

    /**
     * Indice de qualification.
     * @var int
     */
    protected $index = 0;

    /**
     * Nom de qualification.
     * @var string
     */
    protected $name = '';

    /**
     * Instance de l'écran d'affichage associé.
     * @var WpScreenContract
     */
    protected $screen;

    /**
     * CONSTRUCTEUR.
     *
     * @param string $name Nom de qualification.
     * @param array $attrs Liste des attributs de configuration.
     * @param null|string|WP_Screen|WpScreenContract $screen Qualification de la page d'affichage.
     *
     * @return void
     */
    public function __construct($name, $attrs = [], $screen = null)
    {
        $this->name = $name;
        $this->index = self::$_index++;

        if ($screen instanceof WpScreenContract) :
            $this->screen = $screen;
        else :
            add_action('admin_init', function () use ($screen, $attrs) {
                $this->screen = WpScreen::get($screen);

                $content = $this->getContent();
                if (is_string($content) && class_exists($content)) :
                    $resolved = new $content($this);

                    if ($resolved instanceof ColumnDisplayInterface) :
                        $this->set('content', $resolved);
                    endif;
                endif;
            }, 999999);
        endif;

        parent::__construct($attrs);
    }

    /**
     * {@inheritdoc}
     */
    public function getContent()
    {
        return $this->get('content', '');
    }

    /**
     * {@inheritdoc}
     */
    public function getHeader()
    {
        if ($this->getContent() instanceof ColumnDisplayInterface) :
            return $this->getContent()->header();
        else :
            return $this->getTitle();
        endif;
    }

    /**
     * {@inheritdoc}
     */
    public function getIndex()
    {
        return $this->index;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * {@inheritdoc}
     */
    public function getPosition()
    {
        return $this->get('position', 0);
    }

    /**
     * {@inheritdoc}
     */
    public function getScreen()
    {
        return $this->screen;
    }

    /**
     * {@inheritdoc}
     */
    public function getTitle()
    {
        return $this->get('title');
    }

    /**
     * {@inheritdoc}
     */
    public function isActive()
    {
        return $this->active;
    }

    /**
     * {@inheritdoc}
     */
    public function load(WpScreenContract $current_screen)
    {
        if ($this->getScreen() && ($current_screen->getHookname() === $this->getScreen()->getHookname())) :
            $this->active = true;
        endif;
    }
}