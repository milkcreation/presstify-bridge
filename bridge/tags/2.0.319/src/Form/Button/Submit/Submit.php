<?php declare(strict_types=1);

namespace tiFy\Form\Button\Submit;

use tiFy\Form\ButtonController;

class Submit extends ButtonController
{
    /**
     * @inheritDoc
     */
    public function defaults(): array
    {
        return [
            'type'      => 'submit',
            'content'   => __('Envoyer', 'tify')
        ];
    }
}