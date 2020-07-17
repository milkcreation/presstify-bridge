<?php declare(strict_types=1);

namespace tiFy\Validation\Rules;

use Respect\Validation\Rules\{Length, Regex};
use Respect\Validation\Exceptions\ComponentException;
use tiFy\Contracts\Validation\Rule;

class Password extends AbstractRule
{
    /**
     * Nombre de chiffres requis.
     * @var int
     */
    protected $digit = 1;

    /**
     * Nombre de minuscules requises.
     * @var int
     */
    protected $lower = 1;

    /**
     * Longueur maximum.
     * @var int
     */
    protected $max = 0;

    /**
     * Longueur minimum.
     * @var int
     */
    protected $min = 8;

    /**
     * Nombre de caractères spéciaux requis.
     * @var int
     */
    protected $special = 0;

    /**
     * Nombre de majuscules requises.
     * @var int
     */
    protected $upper = 1;

    /**
     * @inheritDoc
     */
    public function setArgs(...$args): Rule
    {
        $args = array_merge($defaults = [
            'digit'   => 1,
            'lower'   => 1,
            'max'     => 16,
            'min'     => 8,
            'special' => 0,
            'upper'   => 1
        ], $args[0] ?? []);

        foreach($args as $k => $v) {
            if (in_array($k, array_keys($defaults))) {
                $this->{$k} = (int)$v;
            }
        }

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function validate($input): bool
    {
        try {
            if ($this->min && !(new Length($this->min))->validate($input)) {
                return false;
            }

            if ($this->max && !(new Length(null, $this->max))->validate($input)) {
                return false;
            }

            $regex = "";

            if ($this->digit) {
                $regex .= "(?=(?:.*\d){" . $this->digit . ",})";
            }

            if ($this->lower) {
                $regex .= "(?=(?:.*[a-z]){" . $this->lower . ",})";
            }

            if ($this->upper) {
                $regex .= "(?=(?:.*[A-Z]){" . $this->upper . ",})";
            }

            if ($this->special) {
                $regex .= "(?=(?:.*[!@#$%^&*()\[\]\-_=+{};:,<.>]){" . $this->special . ",})";
            }

            return (new Regex('/' . $regex . '/'))->validate($input);
        } catch (ComponentException $e) {
            return false;
        }
    }
}