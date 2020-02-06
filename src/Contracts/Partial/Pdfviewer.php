<?php declare(strict_types=1);

namespace tiFy\Contracts\Partial;

interface Pdfviewer extends PartialDriver
{
    /**
     * Récupération de l'instance de la modale associée au PDF.
     *
     * @param array $args Attributs de configuration de la modale.
     *
     * @return Modal
     */
    public function modal(array $args = []): Modal;
}