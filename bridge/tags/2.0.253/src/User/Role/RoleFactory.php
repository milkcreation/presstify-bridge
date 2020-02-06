<?php declare(strict_types=1);

namespace tiFy\User\Role;

use tiFy\Contracts\User\RoleManager;
use tiFy\Contracts\User\RoleFactory as RoleFactoryContract;
use tiFy\Support\ParamsBag;

class RoleFactory extends ParamsBag implements RoleFactoryContract
{
    /**
     * Nom de qualification du rôle.
     * @var string
     */
    protected $name = '';

    /**
     * Instance du gestionnaire.
     * @var RoleManager
     */
    private $manager;

    /**
     * Indicateur de préparation du controleur.
     * @var boolean
     */
    private $_prepared = false;

    /**
     * @inheritdoc
     */
    public function boot(): void
    {

    }

    /**
     * @inheritDoc
     */
    public function defaults(): array
    {
        return [
            'display_name'  => $this->getName(),
            'desc'          => '',
            'capabilities'  => []
        ];
    }

    /**
     * @inheritDoc
     */
    public function getDisplayName(): string
    {
        return $this->get('display_name');
    }

    /**
     * @inheritDoc
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @inheritdoc
     */
    public function parse(): RoleFactoryContract
    {
        parent::parse();

        $capabilities = [];
        foreach ($this->get('capabilities', []) as $cap => $grant) {
            if (is_numeric($cap)) {
                $cap = $grant;
                $grant = true;
            }
            $capabilities[$cap] = $grant;
        }
        $this->set('capabilities', $capabilities);

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function prepare(RoleManager $manager, ?string $name = null, array $attrs = []): RoleFactoryContract
    {
        if (!$this->_prepared) {
            $this->manager = $manager;

            if ($name) {
                $this->name = $name;
            }
            if ($attrs) {
                $this->set($attrs);
            }
            $this->parse();

            events()->trigger('user.role.factory.boot', [$this]);

            $this->boot();
            $this->_prepared = true;
        }
        return $this;
    }
}