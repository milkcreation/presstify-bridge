<?php declare(strict_types=1);

namespace tiFy\Field;

use InvalidArgumentException;
use tiFy\Contracts\Field\{
    Button,
    Checkbox,
    CheckboxCollection,
    Colorpicker,
    DatetimeJs,
    Field as FieldContract,
    FieldFactory,
    File,
    FileJs,
    Hidden,
    Label,
    Number,
    NumberJs,
    Password,
    PasswordJs,
    Radio,
    RadioCollection,
    Repeater,
    Select,
    SelectImage,
    SelectJs,
    Submit,
    Suggest,
    Text,
    Textarea,
    TextRemaining,
    Tinymce,
    ToggleSwitch
};
use tiFy\Support\Manager;

class Field extends Manager implements FieldContract
{
    /**
     * Définition des déclarations des champs par défaut.
     * @var array
     */
    protected $defaults = [
        'button'              => Button::class,
        'checkbox'            => Checkbox::class,
        'checkbox-collection' => CheckboxCollection::class,
        'colorpicker'         => Colorpicker::class,
        'datetime-js'         => DatetimeJs::class,
        'file'                => File::class,
        'file-js'             => FileJs::class,
        'hidden'              => Hidden::class,
        'label'               => Label::class,
        'number'              => Number::class,
        'number-js'           => NumberJs::class,
        'password'            => Password::class,
        'password-js'         => PasswordJs::class,
        'radio'               => Radio::class,
        'radio-collection'    => RadioCollection::class,
        'repeater'            => Repeater::class,
        'select'              => Select::class,
        'select-image'        => SelectImage::class,
        'select-js'           => SelectJs::class,
        'submit'              => Submit::class,
        'suggest'             => Suggest::class,
        'text'                => Text::class,
        'textarea'            => Textarea::class,
        'text-remaining'      => TextRemaining::class,
        'tinymce'             => Tinymce::class,
        'toggle-switch'       => ToggleSwitch::class,
    ];

    /**
     * Liste des éléments déclarées.
     * @var FieldFactory[]
     */
    protected $items = [];

    /**
     * Liste des indices courant des éléments déclarées par alias de qualification.
     * @var int[]
     */
    protected $indexes = [];

    /**
     * Instances des éléments par alias de qualification et indexés par identifiant de qualification.
     * @var FieldFactory[][]
     */
    protected $instances = [];

    /**
     * @inheritDoc
     */
    public function get(...$args): ?FieldFactory
    {
        $alias = $args[0] ?? null;
        if (!$alias || !isset($this->items[$alias])) {
            throw new InvalidArgumentException(
                sprintf(
                    __('Aucune instance de champs correspondant à l\'alias : %s', 'tify'),
                    $alias
                )
            );
        }

        $id = $args[1] ?? null;
        $attrs = $args[2] ?? [];

        if (is_array($id)) {
            $attrs = $id;
            $id = null;
        } else {
            $attrs = $attrs ?: [];
        }

        if ($id) {
            if (!isset($this->instances[$alias][$id])) {
                $this->indexes[$alias]++;
                $this->instances[$alias][$id] = clone $this->items[$alias];
            }
            $partial = $this->instances[$alias][$id];
        } else {
            $this->indexes[$alias]++;
            $partial = clone $this->items[$alias];
        }

        return $partial
            ->setIndex($this->indexes[$alias])
            ->setId($id ?? $alias . $this->indexes[$alias])
            ->set($attrs)->parse();
    }

    /**
     * @inheritDoc
     *
     * @throws InvalidArgumentException
     */
    public function walk(&$item, $key = null): void
    {
        if ($item instanceof FieldFactory) {
            $item->prepare((string)$key, $this);

            $this->instances[$key] = [$item];
            $this->indexes[$key] = 0;
        } else {
            throw new InvalidArgumentException(
                sprintf(
                    __('La déclaration du champ %s devrait être une instance de %s', 'tify'),
                    $key,
                    FieldFactory::class
                )
            );
        }
    }

    /**
     * @inheritDoc
     */
    public function registerDefaults(): FieldContract
    {
        foreach ($this->defaults as $name => $alias) {
            $this->set($name, $this->getContainer()->get($alias));
        }

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function resourcesDir(?string $path = null): string
    {
        $path = $path ? '/' . ltrim($path, '/') : '';

        return (file_exists(__DIR__ . "/Resources{$path}"))
            ? __DIR__ . "/Resources{$path}"
            : '';
    }

    /**
     * @inheritDoc
     */
    public function resourcesUrl(?string $path = null): string
    {
        $cinfo = class_info($this);
        $path = $path ? '/' . ltrim($path, '/') : '';

        return (file_exists($cinfo->getDirname() . "/Resources{$path}"))
            ? $cinfo->getUrl() . "/Resources{$path}"
            : '';
    }
}