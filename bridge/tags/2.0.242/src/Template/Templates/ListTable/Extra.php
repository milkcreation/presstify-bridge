<?php declare(strict_types=1);

namespace tiFy\Template\Templates\ListTable;

use tiFy\Support\ParamsBag;
use tiFy\Template\Factory\FactoryAwareTrait;
use tiFy\Template\Templates\ListTable\Contracts\Extra as ExtraContract;

class Extra extends ParamsBag implements ExtraContract
{
    use FactoryAwareTrait;

    /**
     * Instance du gabarit associé.
     * @var Factory
     */
    protected $factory;

    /**
     * @inheritDoc
     */
    public function __toString(): string
    {
        return $this->render();
    }

    /**
     * @inheritDoc
     */
    public function defaults(): array
    {
        return [
            'content' => '',
            'order'   => 0,
            'which'   => ['top', 'bottom'],
        ];
    }

    /**
     * @inheritDoc
     */
    public function which(): array
    {
        $which = $this->get('which');

        if (is_bool($which)) {
            return $which ? ['top', 'bottom'] : [];
        } elseif (is_string($which)) {
            return [$which];
        } else {
            return is_array($which) ? $which : ['top', 'bottom'];
        }
    }

    /**
     * @inheritDoc
     */
    public function render(): string
    {
        return (string)$this->get('content', '');
    }
}