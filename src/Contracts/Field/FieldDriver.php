<?php declare(strict_types=1);

namespace tiFy\Contracts\Field;

use tiFy\Contracts\Support\ParamsBag;
use tiFy\Contracts\View\Engine as ViewEngine;

interface FieldDriver extends ParamsBag
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
     * @return void
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
     * Récupération du gestionnaire de champs.
     *
     * @return Field|null
     */
    public function manager(): ?Field;

    /**
     * {@inheritDoc}
     *
     * @return static
     */
    public function parse(): FieldDriver;

    /**
     * Traitement de l'attribut "class" de la balise HTML du champ.
     *
     * @return static
     */
    public function parseAttrClass(): FieldDriver;

    /**
     * Traitement de l'attribut "id" de la balise HTML du champ.
     *
     * @return static
     */
    public function parseAttrId(): FieldDriver;

    /**
     * Traitement de l'attribut "name" de la balise HTML du champ.
     *
     * @return static
     */
    public function parseAttrName(): FieldDriver;

    /**
     * Traitement de l'attribut "value" de la balise HTML du champ.
     *
     * @return static
     */
    public function parseAttrValue(): FieldDriver;

    /**
     * Traitement de la liste des attributs par défaut.
     *
     * @return static
     */
    public function parseDefaults(): FieldDriver;

    /**
     * Traitement des attributs de configuration du pilote d'affichage.
     *
     * @return $this
     */
    public function parseViewer(): FieldDriver;

    /**
     * Définition de l'instance de l'élément.
     *
     * @param string $alias Alias de qualification de l'instance dans le gestionnaire.
     * @param Field $manager Instance du gestionnaire.
     *
     * @return static
     */
    public function prepare(string $alias, Field $manager): FieldDriver;

    /**
     * Affichage.
     *
     * @return string
     */
    public function render(): string;

    /**
     * Définition de l'identifiant de qualification.
     *
     * @param string $id
     *
     * @return static
     */
    public function setId(string $id): FieldDriver;

    /**
     * Définition de l'indice de qualification.
     *
     * @param int $index
     *
     * @return static
     */
    public function setIndex(int $index): FieldDriver;

    /**
     * Définition de l'instance du moteur d'affichage.
     *
     * @param ViewEngine $viewer
     *
     * @return static
     */
    public function setViewer(ViewEngine $viewer): FieldDriver;

    /**
     * Instance du gestionnaire de gabarits d'affichage ou rendu du gabarit d'affichage.
     *
     * @param string|null view Nom de qualification du gabarit.
     * @param array $data Liste des variables passées en argument.
     *
     * @return ViewEngine|string
     */
    public function viewer(?string $view = null, array $data = []);
}