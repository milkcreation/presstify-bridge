<?php declare(strict_types=1);

namespace tiFy\Contracts\View;

use tiFy\Contracts\Support\ParamsBag;

interface Engine
{
    /**
     * Vérification d'existance d'un gabarit d'affichage.
     *
     * @param string $name
     *
     * @return bool
     */
    public function exists($name);

    /**
     * @inheritDoc
     */
    public function make($name);

    /**
     * Récupération de l'instance du gestionnaire de vue.
     *
     * @return View
     */
    public function manager(): ?View;

    /**
     * Récupération de paramètre|Définition de paramètres|Instance du gestionnaire de paramètre.
     *
     * @param string|array|null $key Clé d'indice du paramètre à récupérer|Liste des paramètre à définir.
     * @param mixed $default Valeur de retour par défaut lorsque la clé d'indice est une chaine de caractère.
     *
     * @return mixed|ParamsBag
     */
    public function params($key = null, $default = null);

    /**
     * Affichage du gabarit.
     *
     * @param string $name
     * @param array $args Liste des variables passée spécifiquement au gabarit
     *
     * @return string
     */
    public function render($name, array $args = []);

    /**
     * Définition d'une variable partagée passée à l'ensemble des gabarits
     *
     * @param string $key Clé d'indice de la variable.
     * @param mixed $value Valeur de la variable.
     *
     * @return static
     */
    public function share($key, $value = null): Engine;

    /**
     * Définition de l'instance du gestionnaire de vue.
     *
     * @param View $manager
     *
     * @return static
     */
    public function setManager(View $manager);

    /**
     * Définition d'une liste de paramètres.
     *
     * @param array $params
     *
     * @return static
     */
    public function setParams(array $params): Engine;
}