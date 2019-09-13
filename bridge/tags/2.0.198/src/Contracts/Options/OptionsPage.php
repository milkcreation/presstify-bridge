<?php

namespace tiFy\Contracts\Options;

use tiFy\Contracts\Support\ParamsBag;
use tiFy\Contracts\View\ViewController;
use tiFy\Contracts\View\ViewEngine;

interface OptionsPage extends ParamsBag
{
    /**
     * Résolution de sortie de la classe en tant que chaîne de caractère.
     *
     * @return string
     */
    public function __toString();

    /**
     * Initialisation du controleur.
     *
     * @return void
     */
    public function boot();

    /**
     * Affichage.
     *
     * @return string
     */
    public function display();

    /**
     * Récupération de la vue.
     * {@internal Si aucun argument n'est passé à la méthode, retourne l'intance du controleur principal.}
     * {@internal Sinon récupére le gabarit d'affichage et passe les variables en argument.}
     *
     * @param null|string view Nom de qualification du gabarit.
     * @param array $data Liste des variables passées en argument.
     *
     * @return ViewController|ViewEngine
     */
    public function viewer($view = null, $data = []);
}