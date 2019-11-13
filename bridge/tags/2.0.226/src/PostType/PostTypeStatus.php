<?php declare(strict_types=1);

namespace tiFy\PostType;

use tiFy\Contracts\PostType\PostTypeStatus as PostTypeStatusContract;
use tiFy\Support\ParamsBag;

class PostTypeStatus extends ParamsBag implements PostTypeStatusContract
{
    /**
     * Nom de qualification du statut.
     * @var string
     */
    protected $name = '';

    /**
     * CONSTRUCTEUR.
     *
     * @param string $name
     * @param object $args Liste des arguments de qualification du status
     *
     * @return void
     */
    public function __construct(string $name, object $args)
    {
        $this->name = $name;
        $this->set(get_object_vars($args))->parse();
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
    public static function createFromName(string $name): PostTypeStatusContract
    {
        $args = get_post_status_object($name) ? : register_post_status($name);

        return new static($name, $args);
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
        return $this->get('label');
    }

    /**
     * @inheritDoc
     */
    public function getName(): string
    {
        return $this->name;
    }
}