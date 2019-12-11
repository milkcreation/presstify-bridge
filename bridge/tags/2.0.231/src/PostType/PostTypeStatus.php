<?php declare(strict_types=1);

namespace tiFy\PostType;

use tiFy\Contracts\PostType\PostTypeStatus as PostTypeStatusContract;
use tiFy\Support\ParamsBag;

class PostTypeStatus extends ParamsBag implements PostTypeStatusContract
{
    /**
     * Liste des instances déclarées.
     * @var array
     */
    protected static $instances = [];

    /**
     * Nom de qualification du statut.
     * @var string
     */
    protected $name = '';

    /**
     * CONSTRUCTEUR.
     *
     * @param string $name
     *
     * @return void
     */
    protected function __construct(string $name)
    {
        $this->name = $name;
    }

    /**
     * @inheritDoc
     */
    public function __toString(): string
    {
        return $this->name;
    }

    /**
     * @inheritDoc
     */
    public static function create(string $name, array $args = []): PostTypeStatusContract
    {
        if ($exists = get_post_status_object($name)) {
            $args = array_merge(get_object_vars($exists), $args);
        }

        $args = register_post_status($name, $args);

        return static::$instances[$name] = (new static($name))->set(get_object_vars($args))->parse();
    }

    /**
     * @inheritDoc
     */
    public static function instance(string $name): ?PostTypeStatusContract
    {
        return static::$instances[$name] ?? null;
    }

    /**
     * @inheritDoc
     */
    public function defaults(): array
    {
        return [
            'label'                     => $this->name,
            'label_count'               => false,
            'exclude_from_search'       => null,
            '_builtin'                  => false,
            'public'                    => null,
            'internal'                  => null,
            'protected'                 => null,
            'private'                   => null,
            'publicly_queryable'        => null,
            'show_in_admin_status_list' => null,
            'show_in_admin_all_list'    => null,
        ];
    }

    /**
     * @inheritDoc
     */
    public function getLabel(): string
    {
        return (string)$this->get('label');
    }

    /**
     * @inheritDoc
     */
    public function getName(): string
    {
        return $this->name;
    }
}