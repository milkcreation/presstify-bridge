<?php declare(strict_types=1);

namespace tiFy\Contracts\Field;

use tiFy\Contracts\Support\ParamsBag;
use tiFy\Contracts\View\ViewEngine;

interface FieldFactory extends ParamsBag
{
    /**
     * Résolution de sortie de la classe en tant que chaîne de caractère.
     *
     * @return string
     */
    public function __toString(): string;

    /**
     * Post-affichage.
     *
     * @return void
     */
    public function after(): void;

    /**
     * Affichage de la liste des attributs de balise.
     *
     * @return string
     */
    public function attrs(): void;

    /**
     * Pré-affichage.
     *
     * @return void
     */
    public function before(): void;

    /**
     * Initialisation du controleur.
     *
     * @return void
     */
    public function boot(): void;

    /**
     * Affichage du contenu.
     *
     * @return void
     */
    public function content(): void;

    /**
     * Affichage.
     *
     * @return string
     */
    public function display(): string;

    /**
     * Récupération de l'identifiant de qualification dans le gestionnaire.
     *
     * @return string
     */
    public function getAlias(): string;

    /**
     * Récupération de l'identifiant de qualification.
     *
     * @return string
     */
    public function getId(): string;

    /**
     * Récupération de l'indice de qualification dans le gestionnaire.
     *
     * @return int
     */
    public function getIndex(): int;

    /**
     * Récupération de l'indice dans la requête HTTP de soumission.
     *
     * @return string
     */
    public function getName(): string;

    /**
     * Récupération de la valeur dans la requête HTTP de soumission.
     *
     * @return string
     */
    public function getValue();

    /**
     * Vérification de correspondance entre la valeur de coche et celle du champ.
     *
     * @return bool
     */
    public function isChecked();

    /**
     * {@inheritDoc}
     *
     * @return static
     */
    public function parse(): FieldFactory;

    /**
     * Traitement de la liste des attributs par défaut.
     *
     * @return static
     */
    public function parseDefaults(): FieldFactory;

    /**
     * Traitement de l'indice dans la requête HTTP de soumission..
     *
     * @return static
     */
    public function parseName(): FieldFactory;

    /**
     * Traitement de la valeur dans la requête HTTP de soumission.
     *
     * @return static
     */
    public function parseValue(): FieldFactory;

    /**
     * Traitement des attributs de configuration du pilote d'affichage.
     *
     * @return $this
     */
    public function parseViewer(): FieldFactory;

    /**
     * Définition de l'instance de l'élément.
     *
     * @param string $alias Alias de qualification de l'instance dans le gestionnaire.
     * @param Field $manager Instance du gestionnaire.
     *
     * @return static
     */
    public function prepare(string $alias, Field $manager): FieldFactory;

    /**
     * Définition de l'identifiant de qualification.
     *
     * @param string $id
     *
     * @return static
     */
    public function setId(string $id): FieldFactory;

    /**
     * Définition de l'indice de qualification.
     *
     * @param int $index
     *
     * @return static
     */
    public function setIndex(int $index): FieldFactory;

    /**
     * Définition de l'instance du moteur d'affichage.
     *
     * @param ViewEngine $viewer
     *
     * @return static
     */
    public function setViewer(ViewEngine $viewer): FieldFactory;

    /**
     * Récupération d'un instance du controleur de liste des gabarits d'affichage ou d'un gabarit d'affichage.
     * {@internal Si aucun argument n'est passé à la méthode, retourne l'instance du controleur de liste.}
     * {@internal Sinon récupére l'instance du gabarit d'affichage et passe les variables en argument.}
     *
     * @param null|string view Nom de qualification du gabarit.
     * @param array $data Liste des variables passées en argument.
     *
     * @return FieldView|ViewEngine
     */
    public function viewer($view = null, $data = []);
}