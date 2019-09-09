<?php

namespace tiFy\Metabox;

use tiFy\Contracts\Metabox\MetaboxController;
use tiFy\Contracts\Metabox\MetaboxFactory as MetaboxFactoryContract;
use tiFy\Wordpress\Contracts\WpScreen as WpScreenContract;
use tiFy\Wordpress\Routing\WpScreen;
use tiFy\Support\ParamsBag;

class MetaboxFactory extends ParamsBag implements MetaboxFactoryContract
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
     * @param array $attrs {
     *      Attributs de configuration
     *
     * @return array
     * @var string $name Nom de qualification. optionnel, généré automatiquement.
     * @var string|callable $title Titre du greffon.
     * @var string|callable $content Fonction ou méthode ou classe de rappel d'affichage du contenu de la section.
     * @var mixed $args Liste des variables passées en argument dans les fonction d'affichage du titre, du contenu et
     *     dans l'objet.
     * @var string $parent Identifiant de la section parente.
     * @var string|callable@todo $cap Habilitation d'accès.
     * @var bool|callable@todo $show Affichage/Masquage.
     * @var int $position Ordre d'affichage du greffon.
     * }
     *
     */
    protected $attributes = [
        'title'    => '',
        'content'  => '',
        'context'  => 'tab',
        'priority' => 'default',
        'position' => 0,
        'args'     => [],
        'cap'      => 'manage_options',
        'parent'   => '',
        'show'     => true,
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
     * @param null|string|\WP_Screen|WpScreenContract $screen Qualification de la page d'affichage.
     *
     * @return void
     */
    public function __construct($name, $attrs = [], $screen = null)
    {
        $this->name = $name;
        $this->index = self::$_index++;
        $this->set($attrs)->parse();


        if ($screen instanceof WpScreenContract) {
            $this->screen = $screen;
        } else {
            if ($content = $this->get('content', '')) {
                if (is_string($content) && class_exists($content)) {
                    $controller = new $content();
                } elseif (is_object($content)) {
                    $controller = $content;
                    if (is_callable($controller)) {
                        call_user_func($controller);
                    }
                } else {
                    $controller = null;
                }

                if ($controller instanceof MetaboxController) {
                    $controller->set($this->getArgs())->boot();
                    $this->set('controller', $controller);
                }
            }

            add_action('admin_init', function () use ($screen) {
                $this->screen = WpScreen::get($screen);

                $controller = $this->get('controller');

                if ($controller instanceof MetaboxController) {
                    $controller->setFactory($this)->parse()->prepare();

                    $this->set('controller', $controller);
                }
            }, 999999);
        }
    }

    /**
     * @inheritdoc
     */
    public function getArgs()
    {
        return $this->get('args', []);
    }

    /**
     * @inheritdoc
     */
    public function getIndex()
    {
        return $this->index;
    }

    /**
     * @inheritdoc
     */
    public function getContent()
    {
        $content = $this->get('controller') instanceof MetaboxController
            ? call_user_func_array([$this->get('controller'), 'content'], func_get_args())
            : $this->get('content', '');

        if (is_callable($content)) {
            $content = call_user_func_array($content, func_get_args());
        }
        return "<div class=\"MetaboxTab-content\">{$content}</div>";
    }

    /**
     * @inheritdoc
     */
    public function getContext()
    {
        return $this->get('context', 'advanced');
    }

    /**
     * @inheritdoc
     */
    public function getHeader()
    {
        if ($this->get('controller') instanceof MetaboxController) :
            return call_user_func_array([$this->get('controller'), 'header'], func_get_args());
        else :
            return $this->getTitle();
        endif;
    }

    /**
     * @inheritdoc
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @inheritdoc
     */
    public function getParent()
    {
        return $this->get('parent', '');
    }

    /**
     * @inheritdoc
     */
    public function getPosition()
    {
        return $this->get('position', 0);
    }

    /**
     * @inheritdoc
     */
    public function getScreen()
    {
        return $this->screen;
    }

    /**
     * @inheritdoc
     */
    public function getTitle()
    {
        return $this->get('title', '');
    }

    /**
     * @inheritdoc
     */
    public function isActive()
    {
        return $this->active;
    }

    /**
     * @inheritdoc
     */
    public function load(WpScreenContract $current_screen)
    {
        if ($this->getScreen() && ($current_screen->getHookname() === $this->getScreen()->getHookname())) :
            $this->active = true;
        endif;
    }
}