<?php declare(strict_types=1);

namespace tiFy\Wordpress\Contracts\Option;

interface Option
{
    /**
     * Récupération d'une page de réglage des options.
     *
     * @param string $name Nom de qualification de la page
     *
     * @return OptionPage|null
     */
    public function getPage(string $name): ?OptionPage;

    /**
     * Déclaration d'une page de réglage des options.
     *
     * @param string $name Nom de qualification de la page
     * @param OptionPage|array $attrs Instance de la page|Liste des attributs de configuration.
     *
     * @return OptionPage|null
     */
    public function registerPage(string $name, $attrs = []): ?OptionPage;
}