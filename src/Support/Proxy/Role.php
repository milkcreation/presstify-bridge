<?php declare(strict_types=1);

namespace tiFy\Support\Proxy;

use tiFy\Contracts\User\{RoleManager, RoleFactory};

/**
 * @method static RoleFactory|null get(string $name)
 * @method static RoleManager|null register(string $name, array|RoleFactory $attrs = [])
 * @method static RoleManager|null set(string|array $role_name, array|RoleFactory|null $attrs = null)
 */
class Role extends AbstractProxy
{
    public static function getInstanceIdentifier()
    {
        return 'user.role';
    }
}