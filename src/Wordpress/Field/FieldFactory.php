<?php declare(strict_types=1);

namespace tiFy\Wordpress\Field;

use tiFy\Field\FieldFactory as BaseFieldFactory;

abstract class FieldFactory extends BaseFieldFactory
{
    /**
     * @inheritDoc
     */
    public function viewer(?string $view = null, array $data = [])
    {
        if (!$this->viewer && !$this->get('viewer.directory')) {
            $this->set('viewer.directory', __DIR__ . '/Resources/views/' . $this->getAlias());
        }

        return func_num_args() === 0 ? parent::viewer() : parent::viewer($view, $data);
    }
}