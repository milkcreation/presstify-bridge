<?php declare(strict_types=1);

namespace tiFy\Contracts\Template;

interface FactoryAwareTrait
{
    /**
     * Récupération du gabarit d'affichage associé.
     *
     * @return TemplateFactory|null
     */
    public function getFactory(): ?TemplateFactory;

    /**
     * Définition de l'instance du gabarit d'affichage associé.
     *
     * @param TemplateFactory $factory.
     *
     * @return static
     */
    public function setTemplateFactory(TemplateFactory $factory): FactoryAwareTrait;
}