<?php declare(strict_types=1);

namespace tiFy\Wordpress\Database\Model;

use Exception;
use Corcel\Model\Option as CorcelOption;
use Illuminate\Database\Eloquent\Builder;
use tiFy\Support\Str;

/**
 * @mixin Builder
 */
class Option extends CorcelOption
{
    public function getAttribute($key)
    {
        if ($key === 'option_value') {
            try {
                if (!$value = $this->attributes['option_value'] ?? null) {
                    return parent::getAttribute($key);
                }

                return  is_string($value) ? Str::unserialize($value) : $value;
            } catch (Exception $e) {
                return parent::getAttribute($key);
            }
        } else {
            return parent::getAttribute($key);
        }

    }
}
