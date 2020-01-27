<?php

namespace tiFy\Form\Factory;

use tiFy\Contracts\Form\FactorySession;
use tiFy\Contracts\Form\FormFactory;
use tiFy\Support\ParamsBag;

class Session extends ParamsBag implements FactorySession
{
    use ResolverTrait;

    /**
     * Identifiant de qualification.
     * @var string
     */
    protected $id = '';

    /**
     * Délai d'expiration du cache
     * {@internal MINUTE_IN_SECONDS|HOUR_IN_SECONDS|DAY_IN_SECONDS|WEEK_IN_SECONDS|YEAR_IN_SECONDS}
     * @var int
     */
    protected $expiration = HOUR_IN_SECONDS;

    /**
     * CONSTRUCTEUR.
     *
     * @param FormFactory $form Instance du contrôleur de formulaire.
     *
     * @return void
     */
    public function __construct(FormFactory $form)
    {
        $this->form = $form;
    }

    /**
     * Destruction de l'identifiant de qualification.
     *
     * @return void
     */
    public function clear()
    {
        $this->id = '';
    }

    /**
     * Génération d'un identifiant de qualification.
     *
     * @return string
     */
    public function create()
    {
        return wp_hash(uniqid() . $this->form()->name());
    }

    /**
     * Récupération l'identifiant de session.
     *
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Initialisation de la session.
     *
     * @return string
     */
    public function init()
    {
        if ($this->getId()) :
        elseif ($this->id = $this->request()->get('_session-' . $this->form()->name())) :
        else :
            $this->id = $this->create();
        endif;

        return $this->getId();
    }

    /**
     * Préparation de la liste des attributs de session.
     *
     * @return void
     */
    public function prepare()
    {
        $this->attributes = get_transient("_form_session-{$this->getId()}") ? : [];
    }
}