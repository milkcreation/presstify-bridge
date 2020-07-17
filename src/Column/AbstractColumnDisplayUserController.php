<?php

namespace tiFy\Column;

use tiFy\Contracts\Column\ColumnDisplayUserInterface;

class AbstractColumnDisplayUserController extends AbstractColumnDisplayController
    implements ColumnDisplayUserInterface
{
    /**
     * {@inheritdoc}
     */
    public function content($content = null, $column_name = null, $user_id = null)
    {
        parent::content($content, $column_name, $user_id);
    }
}