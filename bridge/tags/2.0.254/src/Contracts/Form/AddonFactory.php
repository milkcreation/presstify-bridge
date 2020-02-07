<?php declare(strict_types=1);

namespace tiFy\Contracts\Form;

use InvalidArgumentException;
use LogicException;
use tiFy\Contracts\Support\ParamsBag;

interface AddonFactory
{
    /**
     * Initialisation de l'instance.
     *
     * @return void
     */
    public function boot(): void;

    /**
     * Liste des paramètres par défaut.
     *
     * @return array
     */
    public function defaultsParams(): array;

    /**
     * Liste des attributs de configuration par défaut du formulaire associé.
     *
     * @return array
     */
    public function defaultsFormOptions(): array;

    /**
     * Liste des attributs de configuration par défaut des champs du formulaire associé.
     *
     * @return array
     */
    public function defaultsFieldOptions(): array;

    /**
     * Récupération de l'instance du formulaire associé.
     *
     * @return FormFactory
     *
     * @throws LogicException
     */
    public function form(): FormFactory;

    /**
     * Récupération du nom de qualification.
     *
     * @return string
     */
    public function name();

    /**
     * Définition de paramètre|Récupération de paramètres|Récupération de l'instance des paramètres.
     *
     * @param array|string|null $key Liste des définitions de paramètres|Indice de qualification du paramètres à récupérer (Syntaxe à point permise).
     * @param mixed $default Valeur de retour par défaut lors de la récupération de paramètres.
     *
     * @return mixed|ParamsBag
     *
     * @throws InvalidArgumentException
     */
    public function params($key = null, $default = null);

    /**
     * Traitement de la liste des paramètres.
     *
     * @return void
     */
    public function parseParams(): void;

    /**
     * Définition de l'instance du formulaire associé.
     *
     * @param FormFactory $form
     *
     * @return static
     */
    public function setForm(FormFactory $form): AddonFactory;

    /**
     * Définition du nom de qualification.
     *
     * @param string $name
     *
     * @return static
     */
    public function setName(string $name): AddonFactory;

    /**
     * Définition de la liste des paramètres.
     *
     * @param array $params
     *
     * @return static
     */
    public function setParams(array $params): AddonFactory;
}