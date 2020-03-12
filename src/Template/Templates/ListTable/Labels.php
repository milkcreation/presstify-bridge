<?php declare(strict_types=1);

namespace tiFy\Template\Templates\ListTable;

use tiFy\Template\Factory\Labels as BaseLabels;

class Labels extends BaseLabels
{
    /**
     * Instance du gabarit associé.
     * @var Factory
     */
    protected $factory;

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