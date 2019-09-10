<?php

namespace tiFy\User\Query;

use tiFy\Contracts\User\UserQueryItem as UserQueryItemContract;
use tiFy\Kernel\Params\ParamsBag;

/**
 * Class UserQueryItem
 * @package tiFy\User\Query
 *
 * @deprecated Utiliser \tiFy\Wordpress\Query\QueryUser en remplacement.
 */
class UserQueryItem extends ParamsBag implements UserQueryItemContract
{
    /**
     * Objet User Wordpress
     * @var \WP_User
     */
    protected $object;

    /**
     * CONSTRUCTEUR
     *
     * @param \WP_User $wp_user
     *
     * @return void
     */
    public function __construct(\WP_User $wp_user)
    {
        $this->object = $wp_user;

        parent::__construct($this->object->to_array());
    }

    /**
     * {@inheritdoc}
     */
    public function can($capability)
    {
        $args = array_slice(func_get_args(), 1);
        $args = array_merge([$capability], $args);

        return call_user_func_array([$this->getUser(), 'has_cap'], $args);
    }

    /**
     * {@inheritdoc}
     */
    public function getDescription()
    {
        return $this->getUser()->description;
    }

    /**
     * {@inheritdoc}
     */
    public function getDisplayName()
    {
        return (string)$this->get('display_name', '');
    }

    /**
     * {@inheritdoc}
     */
    public function getEmail()
    {
        return (string)$this->get('user_email', '');
    }

    /**
     * {@inheritdoc}
     */
    public function getFirstName()
    {
        return $this->getUser()->first_name;
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return (int)$this->get('ID', 0);
    }

    /**
     * {@inheritdoc}
     */
    public function getLastName()
    {
        return $this->getUser()->last_name;
    }

    /**
     * {@inheritdoc}
     */
    public function getLogin()
    {
        return (string)$this->get('user_login', '');
    }

    /**
     * {@inheritdoc}
     */
    public function getNicename()
    {
        return (string)$this->get('user_nicename', '');
    }

    /**
     * {@inheritdoc}
     */
    public function getNickname()
    {
        return $this->getUser()->nickname;
    }

    /**
     * {@inheritdoc}
     */
    public function getPass()
    {
        return (string)$this->get('user_pass', '');
    }

    /**
     * {@inheritdoc}
     */
    public function getRegistered()
    {
        return (string)$this->get('user_registered', '');
    }

    /**
     * {@inheritdoc}
     */
    public function getRoles()
    {
        return $this->getUser()->roles;
    }

    /**
     * {@inheritdoc}
     */
    public function getUrl()
    {
        return (string)$this->get('user_url', '');
    }

    /**
     * {@inheritdoc}
     */
    public function getUser()
    {
        return $this->object;
    }

    /**
     * {@inheritdoc}
     */
    public function hasRole($role)
    {
        return in_array($role, $this->getRoles());
    }

    /**
     * {@inheritdoc}
     */
    public function isLoggedIn()
    {
        return (\get_current_user_id()) && (\get_current_user_id() === $this->getId());
    }
}