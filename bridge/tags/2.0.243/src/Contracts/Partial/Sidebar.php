<?php declare(strict_types=1);

namespace tiFy\Contracts\Partial;

interface Sidebar extends PartialDriver
{
    /**
     * Lien de bascule d'affichage de la sidebar.
     *
     * @param array $attrs Liste des attributs de configuration.
     *
     * @return string
     */
    public function toggle(array $attrs = []): string;
}