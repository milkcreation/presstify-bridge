<?php declare(strict_types=1);

namespace tiFy\Template\Templates\PostListTable;

use tiFy\Contracts\Template\FactoryLabels as FactoryLabelsContract;
use tiFy\PostType\PostTypeLabelsBag;
use tiFy\Template\Factory\FactoryAwareTrait;

class Labels extends PostTypeLabelsBag implements FactoryLabelsContract
{
    use FactoryAwareTrait;

    /**
     * Instance du gabarit d'affichage.
     * @var Factory
     */
    protected $factory;

    /**
     * @inheritDoc
     */
    /**
     * @inheritDoc
     */
    public function defaults(): array
    {
        return array_merge(parent::defaults(), [
            'all_items' => sprintf(
                $this->gender() ? __('Toutes les %s', 'tify') : __('Tous les %s', 'tify'),
                $this->plural()
            ),
            'no_item' => sprintf(
                $this->gender() ? __('Aucune %s trouvée.', 'tify') : __('Aucun %s trouvé.', 'tify'),
                $this->singular()
            ),
            'page_title' => sprintf(
                $this->gender() ? __('Toutes les %s', 'tify') : __('Tous les %s', 'tify'),
                $this->plural()
            ),
            'search_item' => sprintf(
                $this->gender() ? __('Rechercher une %s', 'tify') : __('Rechercher un %s', 'tify'),
                $this->singular()
            )
        ]);
    }
}