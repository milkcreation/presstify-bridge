<?php declare(strict_types=1);

namespace tiFy\Wordpress\Contracts\Database;

use Illuminate\Database\Eloquent\{Model, Builder};

/**
 * @mixin Model
 * @mixin Builder
 */
interface BlogAwareTrait
{
    /**
     * Récupération du prefixe des tables d'un blog.
     *
     * @param int|null $blog_id
     *
     * @return string
     */
    public function getBlogPrefix(?int $blog_id = null): string;
}