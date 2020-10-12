<?php declare(strict_types=1);

namespace tiFy\Support\Proxy;

use tiFy\Contracts\PostType\{PostType as PostTypeContract, PostTypeFactory, PostTypePostMeta, PostTypeStatus};

/**
 * @method static PostTypeFactory|null get(string $name)
 * @method static PostTypePostMeta meta()
 * @method static PostTypeFactory register(string $name, array|PostTypeFactory $args = [])
 * @method static PostTypeStatus status(string $name, array $args = [])
 */
class PostType extends AbstractProxy
{
    /**
     * {@inheritDoc}
     *
     * @return PostTypeContract
     */
    public static function getInstance()
    {
        return parent::getInstance();
    }

    /**
     * @inheritDoc
     */
    public static function getInstanceIdentifier()
    {
        return 'post-type';
    }
}