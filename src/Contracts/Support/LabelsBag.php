<?php declare(strict_types=1);

namespace tiFy\Contracts\Support;

interface LabelsBag extends ParamsBag
{
    /**
     * Récupération du nom de qualification.
     *
     * @return string
     */
    public function getName(): string;

    /**
     * Vérification du genre.
     *
     * @return boolean
     */
    public function gender(): bool;

    /**
     * @inheritDoc
     */
    public function parse(): LabelsBag;

    /**
     * Récupération du pluriel.
     *
     * @param boolean $ucfirst Mise en majuscule de la première lettre.
     *
     * @return string
     */
    public function plural(bool $ucfirst = false): string;

    /**
     * Récupération du pluriel précédé d'un article défini.
     *
     * @param boolean $contraction Activation de la forme contractée.
     *
     * @return string
     */
    public function pluralDefinite(bool $contraction = false): string;

    /**
     * Récupération du pluriel précédé d'un article indéfini.
     *
     * @return string
     */
    public function pluralIndefinite(): string;

    /**
     * Récupération du singulier.
     *
     * @param boolean $ucfirst Mise en majuscule de la première lettre.
     *
     * @return string
     */
    public function singular(bool $ucfirst = false): string;

    /**
     * Récupération du singulier précédé d'un article défini.
     *
     * @param boolean $contraction Activation de la forme contractée.
     *
     * @return string
     */
    public function singularDefinite(bool $contraction = false): string;

    /**
     * Récupération du singulier précédé d'un article indéfini.
     *
     * @return string
     */
    public function singularIndefinite(): string;

    /**
     * Définition du genre de l'élément
     *
     * @param bool $gender
     *
     * @return static
     */
    public function setGender(bool $gender): LabelsBag;

    /**
     * Définition du nom de qualification.
     *
     * @param string $name
     *
     * @return static
     */
    public function setName(string $name): LabelsBag;

    /**
     * Définition de l'intitulé du pluriel d'un élément.
     *
     * @param string $plural
     *
     * @return static
     */
    public function setPlural(string $plural): LabelsBag;

    /**
     * Définition de l'intitulé du singulier d'un élément.
     *
     * @param string $singular
     *
     * @return static
     */
    public function setSingular(string $singular): LabelsBag;

    /**
     * Permet de vérifier si la première lettre d'une chaîne de caractère est une voyelle.
     *
     * @return boolean
     */
    public function useVowel(): bool;
}