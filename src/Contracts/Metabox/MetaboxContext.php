<?php declare(strict_types=1);

namespace tiFy\Contracts\Metabox;

use tiFy\Contracts\{Support\ParamsBag, View\PlatesEngine};

interface MetaboxContext extends ParamsBag
{
    /**
     * Résolution de sortie de la classe sous forme de chaîne de caractères.
     *
     * @return string
     */
    public function __toString(): string;

    /**
     * Récupération du nom de qualification.
     *
     * @return string
     */
    public function getName(): string;

    /**
     * Récupération de l'instance du gestionnaire.
     *
     * @return MetaboxManager|null
     */
    public function manager(): ?MetaboxManager;

    /**
     * {@inheritDoc}
     *
     * @return MetaboxContext
     */
    public function parse();

    /**
     * Récupération du rendu d'affichage du contexte.
     *
     * @return string
     */
    public function render(): string;

    /**
     * Définition de l'instance du gestionnaire.
     *
     * @param MetaboxManager $manager
     *
     * @return static
     */
    public function setManager(MetaboxManager $manager): MetaboxContext;

    /**
     * Définition du nom de qualification.
     *
     * @param string $name
     *
     * @return static
     */
    public function setName(string $name): MetaboxContext;

    /**
     * Récupération d'un instance du controleur de liste des gabarits d'affichage ou d'un gabarit d'affichage.
     * {@internal Si aucun argument n'est passé à la méthode, retourne l'instance du controleur de liste.}
     * {@internal Sinon récupére l'instance du gabarit d'affichage et passe les variables en argument.}
     *
     * @param string|null view Nom de qualification du gabarit.
     * @param array $data Liste des variables passées en argument.
     *
     * @return PlatesEngine|string
     */
    public function viewer(?string $view = null, array $data = []);
}