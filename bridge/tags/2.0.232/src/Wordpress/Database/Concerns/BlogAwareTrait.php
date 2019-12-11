<?php declare(strict_types=1);

namespace tiFy\Wordpress\Database\Concerns;

use tiFy\Wordpress\Contracts\Database\BlogAwareTrait as BlogAwareTraitContract;

/**
 * @mixin BlogAwareTraitContract
 */
trait BlogAwareTrait
{
    /**
     * @inheritDoc
     */
    public function getBlogPrefix(?int $blog_id = null): string
    {
        $base_prefix = $this->getConnection()->getTablePrefix();

        if (is_multisite()) {
            if (null === $blog_id) {
                $blog_id = get_current_blog_id();
            }
            if (defined('MULTISITE') && (0 == $blog_id || 1 == $blog_id)) {
                return $base_prefix;
            } else {
                return $base_prefix . $blog_id . '_';
            }
        } else {
            return $base_prefix;
        }
    }
}