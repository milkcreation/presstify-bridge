<?php declare(strict_types=1);

namespace tiFy\Wordpress\View;

use tiFy\Contracts\View\View as BaseViewContract;

class View
{
    /**
     * Instance du gestionnaire de routage.
     * @var BaseViewContract
     */
    protected $manager;

    /**
     * CONSTRUCTEUR.
     *
     * @param BaseViewContract $manager Instance du gestionnaire de gabarits d'affichage.
     *
     * @return void
     */
    public function __construct(BaseViewContract $manager)
    {
        $this->manager = $manager;
    }
}