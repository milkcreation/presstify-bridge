<?php declare(strict_types=1);

namespace tiFy\Wordpress\PageHook;

use tiFy\Contracts\Routing\Route;
use tiFy\Support\ParamsBag;
use tiFy\Wordpress\Contracts\{
    PageHookItem as PageHookItemContract,
    Query\QueryPost as QueryPostContract,
};
use WP_Post;

class PageHookFaker extends ParamsBag implements PageHookItemContract
{
    /**
     * Nom de qualification.
     * @var string
     */
    protected $name = '';

    /**
     * CONSTRUCTEUR.
     *
     * @param string $name Nom de qualification.
     * @param array $attrs Liste des attributs de configuration.
     *
     * @return void
     */
    public function __construct(string $name, array $attrs = [])
    {
        $this->name = $name;
    }

    /**
     * @inheritDoc
     */
    public function defaults(): array
    {
        return [];
    }

    /**
     * @inheritDoc
     */
    public function exists(): bool
    {
        return false;
    }

    /**
     * @inheritDoc
     */
    public function getDescription(): string
    {
        return '';
    }

    /**
     * @inheritDoc
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @inheritDoc
     */
    public function getPath(): string
    {
        return '';
    }

    /**
     * @inheritDoc
     */
    public function getObjectType(): string
    {
        return '';
    }

    /**
     * @inheritDoc
     */
    public function getObjectName(): string
    {
        return '';
    }

    /**
     * @inheritDoc
     */
    public function getOptionName(): string
    {
        return '';
    }

    /**
     * @inheritDoc
     */
    public function getTitle(): string
    {
        return '';
    }

    /**
     * @inheritDoc
     */
    public function is(?WP_Post $post = null): bool
    {
        return false;
    }

    /**
     * @inheritDoc
     */
    public function isAncestor(): bool
    {
        return false;
    }

    /**
     * @inheritDoc
     */
    public function post(): ?QueryPostContract
    {
        return null;
    }

    /**
     * @inheritDoc
     */
    public function route(): ?Route
    {
        return null;
    }
}