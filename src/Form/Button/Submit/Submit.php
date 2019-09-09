<?php

namespace tiFy\Form\Button\Submit;

use tiFy\Form\ButtonController;

class Submit extends ButtonController
{
    /**
     * {@inheritdoc}
     */
    public function defaults()
    {
        return [
            'type'      => 'submit',
            'content'   => __('Envoyer', 'tify')
        ];
    }
}