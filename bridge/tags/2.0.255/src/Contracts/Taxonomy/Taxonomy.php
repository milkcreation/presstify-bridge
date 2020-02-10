<?php declare(strict_types=1);

namespace tiFy\Contracts\Taxonomy;

use Psr\Container\ContainerInterface as Container;

interface Taxonomy
{
    /**
     * Récupération d'une instance de type de post.
     *
     * @param string $name Nom de qualification du type de post.
     *
     * @return TaxonomyFactory|null
     */
    public function get(string $name): ?TaxonomyFactory;

    /**
     * Récupération de l'instance du conteneur d'injection de dépendances.
     *
     * @return Container|null
     */
    public function getContainer(): ?Container;

    /**
     * Récupération de l'instance du controleur de metadonnées de post.
     *
     * @return TaxonomyTermMeta|null
     */
    public function meta(): ?TaxonomyTermMeta;

    /**
     * Déclaration d'un type de post.
     *
     * @param string $name Nom de qualification du type de post.
     * @param array $args Liste des arguments de configuration.
     *
     * @return TaxonomyFactory|null
     */
    public function register(string $name, array $args = []): ?TaxonomyFactory;

    /**
     * Récupération de l'instance du controleur de metadonnées de terme.
     *
     * @deprecated
     *
     * @return TaxonomyTermMeta|null
     */
    public function term_meta(): ?TaxonomyTermMeta;
}