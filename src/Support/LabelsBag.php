<?php declare(strict_types=1);

namespace tiFy\Support;

use tiFy\Contracts\Support\{LabelsBag as LabelsBagContract, ParamsBag as ParamsBagContract};

class LabelsBag extends ParamsBag implements LabelsBagContract
{
    /**
     * Indicateur de gestion du féminin.
     * @var boolean
     */
    protected $gender = false;

    /**
     * Nom de qualification.
     * @var string
     */
    protected $name = '';

    /**
     * Intitulé de qualification du pluriel d'un élément.
     * @var string
     */
    protected $plural = '';

    /**
     * Intitulé de qualification du singulier d'un élément.
     * @var string
     */
    protected $singular = '';

    /**
     * @inheritDoc
     *
     * @return LabelsBagContract
     */
    public static function createFromAttrs($attrs, ?string $name = null): ParamsBagContract
    {
        $self = new static();
        if (!is_null($name)) {
            $self->setName($name);
        }

        return $self->set($attrs)->parse();
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
    public function gender(): bool
    {
        return $this->gender;
    }

    /**
     * @inheritDoc
     */
    public function parse(): LabelsBagContract
    {
        if ($this->has('gender')) {
            $this->setGender(!!$this->pull('gender'));
        }

        if ($this->has('plural')) {
            $this->setPlural(Str::lower($this->pull('plural')));
        }

        if ($this->has('singular')) {
            $this->setSingular(Str::lower($this->pull('singular')));
        }

        parent::parse();

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function plural(bool $ucfirst = false): string
    {
        $str = $this->plural ? : __('éléments', 'tify');

        return $ucfirst ? Str::ucfirst($str) : $str;
    }

    /**
     * @inheritDoc
     */
    public function pluralDefinite(bool $contraction = false): string
    {
        if ($contraction) {
            $prefix = __('des', 'tify') . ' ';

            return $prefix . $this->plural();
        } else {
            $prefix = __('les', 'tify') . ' ';

            return $prefix . $this->plural();
        }
    }

    /**
     * @inheritDoc
     */
    public function pluralIndefinite(): string
    {
        $prefix = $this->useVowel() ? __('d\'', 'tify') : __('des', 'tify') . ' ';

        return $prefix . $this->plural();
    }

    /**
     * @inheritDoc
     */
    public function singular(bool $ucfirst = false): string
    {
        $str = $this->singular ? : __('élément', 'tify');

        return $ucfirst ? Str::ucfirst($str) : $str;
    }

    /**
     * @inheritDoc
     */
    public function singularDefinite(bool $contraction = false): string
    {
        if ($contraction) {
            $prefix = $this->useVowel()
                ? __('de l\'', 'tify')
                : ($this->gender() ? __('de la', 'tify') . ' ' : __('du', 'tify'). ' ');

            return $prefix . $this->singular();
        } else {
            $prefix = $this->useVowel()
                ? __('l\'', 'tify')
                : ($this->gender() ? __('la', 'tify') . ' ' : __('le ', 'tify') . ' ');

            return $prefix . $this->singular();
        }
    }

    /**
     * @inheritDoc
     */
    public function singularIndefinite(): string
    {
        $prefix = $this->gender() ? __('une', 'tify') . ' ' : __('un', 'tify') . ' ';

        return $prefix . $this->singular();
    }

    /**
     * @inheritDoc
     */
    public function setGender(bool $gender): LabelsBagContract
    {
        $this->gender = $gender;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setName(string $name): LabelsBagContract
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setPlural(string $plural): LabelsBagContract
    {
        $this->plural = $plural;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setSingular(string $singular): LabelsBagContract
    {
        $this->singular = $singular;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function useVowel(): bool
    {
        $first = strtolower(mb_substr(remove_accents($this->singular()), 0, 1));

        return in_array($first, ['a', 'e', 'i', 'o', 'u', 'y']);
    }
}