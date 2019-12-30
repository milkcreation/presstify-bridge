<?php declare(strict_types=1);

namespace tiFy\Contracts\Form;

use tiFy\Contracts\View\PlatesFactory;

interface FactoryView extends PlatesFactory
{
    /**
     * Post-affichage.
     *
     * @return string
     */
    public function after(): string;

    /**
     * Pré-affichage.
     *
     * @return string
     */
    public function before(): string;

    /**
     * Récupération de l'instance du contrôleur de formulaire.
     *
     * @return FormFactory
     */
    public function form(): FormFactory;
}