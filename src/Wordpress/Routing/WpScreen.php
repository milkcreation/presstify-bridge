<?php

namespace tiFy\Wordpress\Routing;

use tiFy\Wordpress\Contracts\WpScreen as WpScreenContract;
use WP_Screen;

class WpScreen implements WpScreenContract
{
    /**
     * Instance de l'écran en relation.
     * @var WP_Screen
     */
    protected $screen;

    /**
     * Nom de qualification de l'objet Wordpress associé.
     * @var string
     */
    protected $objectName = '';

    /**
     * Typel'objet Wordpress associé.
     * @var string
     */
    protected $objectType = '';

    /**
     * CONSTRUCTEUR.
     *
     * @param WP_Screen $wp_screen Objet screen Wordpress.
     *
     * @return void
     */
    public function __construct(WP_Screen $wp_screen)
    {
        $this->screen = $wp_screen;

        $this->parse();
    }

    /**
     * @inheritdoc
     */
    public static function get($screen = '')
    {
        if ($screen instanceof WpScreenContract) :
            return $screen;
        elseif ($screen instanceof WP_Screen) :
            return new static($screen);
        elseif (is_string($screen)):
            if (preg_match('#(edit|list)::(.*)@(post_type|taxonomy|user)#', $screen, $matches)) :
                if ($matches[1] === 'edit') :
                    switch ($matches[3]) :
                        case 'post_type' :
                            $attrs = [
                                'id'        => $matches[2],
                                'base'      => 'post',
                                'action'    => '',
                                'post_type' => $matches[2],
                                'taxonomy'  => ''
                            ];
                            break;
                        case 'taxonomy' :
                            $attrs = [
                                'id'        => 'edit-' . $matches[2],
                                'base'      => 'term',
                                'action'    => '',
                                'post_type' => '',
                                'taxonomy'  => $matches[2]
                            ];
                            break;
                        case 'user' :
                            $attrs = [
                                'id'        => 'users-edit',
                                'base'      => 'users-edit',
                                'action'    => '',
                                'post_type' => '',
                                'taxonomy'  => '',
                            ];
                            break;
                    endswitch;
                elseif ($matches[1] === 'list') :
                    switch ($matches[3]) :
                        case 'post_type' :
                            $attrs = [
                                'id'        => 'edit-' . $matches[2],
                                'base'      => 'edit',
                                'action'    => '',
                                'post_type' => $matches[2],
                                'taxonomy'  => ''
                            ];
                            break;
                        case 'taxonomy' :
                            $attrs = [
                                'id'        => 'edit-' . $matches[2],
                                'base'      => 'edit-tags',
                                'action'    => '',
                                'post_type' => '',
                                'taxonomy'  => $matches[2],
                            ];
                            break;
                        case 'user' :
                            $attrs = [
                                'id'        => 'users',
                                'base'      => 'users',
                                'action'    => '',
                                'post_type' => '',
                                'taxonomy'  => '',
                            ];
                            break;
                    endswitch;
                endif;

                $screen = clone WP_Screen::get($attrs['id']?? '');
                foreach ($attrs as $key => $value) :
                    $screen->{$key} = $value;
                endforeach;
            elseif (preg_match('#(.*)@(options)#', $screen, $matches)) :
                switch ($matches[2]) :
                    case 'options' :
                        $screen = clone WP_Screen::get('settings_page_' . $matches[1]);
                        break;
                endswitch;
            else :
                $screen = clone WP_Screen::get($screen);
            endif;

            if ($screen instanceof WP_Screen) :
                return new static($screen);
            endif;
        endif;

        return null;
    }

    /**
     * @inheritdoc
     */
    public function getHookname()
    {
        return $this->getScreen()->id;
    }

    /**
     * @inheritdoc
     */
    public function getObjectName()
    {
        return $this->objectName;
    }

    /**
     * @inheritdoc
     */
    public function getObjectType()
    {
        return $this->objectType;
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
    public function isCurrent()
    {
        return (($current_screen = get_current_screen()) && ($current_screen->id === $this->getHookname()));
    }

    /**
     * @inheritdoc
     */
    public function parse()
    {
        if (preg_match('#^settings_page_(.*)#', $this->screen->id, $matches)) :
            $this->objectName = $matches[1];
            $this->objectType = 'options';
        elseif (
            ($this->screen->base === 'term') &&
            preg_match('#^edit-(.*)#', $this->screen->id, $matches) &&
            taxonomy_exists($matches[1])
        ) :
            $this->objectName = $matches[1];
            $this->objectType = 'taxonomy';
        elseif (
            ($this->screen->base === 'edit-tags') &&
            preg_match('#^edit-(.*)#', $this->screen->id, $matches) &&
            taxonomy_exists($matches[1])
        ) :
            $this->objectName = $matches[1];
            $this->objectType = 'taxonomy';
        elseif (
            ($this->screen->base === 'edit') &&
            preg_match('#^edit-(.*)#', $this->screen->id, $matches) &&
            post_type_exists($matches[1])
        ) :
            $this->objectName = $matches[1];
            $this->objectType = 'post_type';
        elseif (post_type_exists($this->screen->id)) :
            $this->objectName = $this->screen->id;
            $this->objectType = 'post_type';
        endif;
    }
}