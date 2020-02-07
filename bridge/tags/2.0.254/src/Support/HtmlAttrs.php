<?php declare(strict_types=1);

namespace tiFy\Support;

use tiFy\Contracts\Support\HtmlAttrs as HtmlAttrsContract;

class HtmlAttrs implements HtmlAttrsContract
{
    /**
     * Liste des attributs HTML.
     * @var array
     */
    protected $attributes = [];

    /**
     * @inheritdoc
     */
    public static function createFromAttrs(array $attrs, $linearized = true)
    {
        $self = new static($attrs);

        return $linearized ? (string)$self : (array)$self->attributes;
    }

    /**
     * Convertion d'une liste d'attributs en attributs HTML.
     *
     * @param array $attrs Liste des attributs.
     *
     * @return void
     */
    public function __construct(array $attrs)
    {
        $this->set($attrs);
    }

    /**
     * @inheritdoc
     */
    public function __toString(): string
    {
        return implode(' ', $this->attributes);
    }

    /**
     * @inheritdoc
     */
    public function all(): array
    {
        return $this->attributes;
    }

    /**
     * @inheritdoc
     */
    public function arrayEncode(array $value): string
    {
        return rawurlencode(json_encode($value));
    }

    /**
     * @inheritdoc
     */
    public function set(array $attrs): HtmlAttrsContract
    {
        array_walk($attrs, [$this, 'walk']);

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function walk($value, $key = null): void
    {
        if (is_array($value)) {
            $value = $this->arrayEncode($value);
        }
        $this->attributes[] = is_numeric($key) ? "{$value}" : "{$key}=\"{$value}\"";
    }
}