<?php declare(strict_types=1);

namespace tiFy\Wordpress\Contracts;

interface WpQuery
{
    /**
     * Vérifie si la page d'affichage courante correspond au contexte indiqué.
     *
     * @param string $ctags Identifiant de qualification du contexte. ex. 404|archive|singular...
     *
     * @return boolean
     */
    public function is($ctag): bool;

    /**
     * Récupération de l'alias de contexte de la page d'affichage courante.
     *
     * @return string|null
     */
    public function ctag(): ?string;
}