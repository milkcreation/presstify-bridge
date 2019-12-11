<?php declare(strict_types=1);

namespace tiFy\Contracts\User;

use tiFy\Contracts\Support\ParamsBag;
use tiFy\Contracts\Form\FormFactory;

interface SignupFactory extends ParamsBag
{
    /**
     * Résolution de sortie de la classe en tant que chaîne de caractère.
     * {@internal Affiche le formulaire}
     *
     * @return string
     */
    public function __toString(): string;

    /**
     * Récupération du nom de qualification du controleur.
     *
     * @return string
     */
    public function getName(): string;

    /**
     * Récupération du formulaire.
     *
     * @return FormFactory|null
     */
    public function form(): ?FormFactory;
}