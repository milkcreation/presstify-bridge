<?php

namespace tiFy\Wp;

class WpManager
{
    /**
     * CONSTRUCTEUR
     *
     * @return void
     */
    public function __construct()
    {
        /*if ($this->is()) :
            config(['site_url' => site_url()]);
        endif;*/
    }

    /**
     * @inheritdoc
     */
    public function is()
    {
        return true;
    }
}