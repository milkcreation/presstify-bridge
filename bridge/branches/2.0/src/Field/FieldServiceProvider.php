<?php declare(strict_types=1);

namespace tiFy\Field;

use tiFy\Container\ServiceProvider;
use tiFy\Contracts\Field\{
    Button as ButtonContract,
    Checkbox as CheckboxContract,
    CheckboxCollection as CheckboxCollectionContract,
    Colorpicker as ColorpickerContract,
    Field as FieldContract,
    FieldFactory,
    File as FileContract,
    FileJs as FileJsContract,
    DatetimeJs as DatetimeJsContract,
    Hidden as HiddenContract,
    Label as LabelContract,
    Number as NumberContract,
    NumberJs as NumberJsContract,
    Password as PasswordContract,
    PasswordJs as PasswordJsContract,
    Radio as RadioContract,
    RadioCollection as RadioCollectionContract,
    Repeater as RepeaterContract,
    Select as SelectContract,
    SelectImage as SelectImageContract,
    SelectJs as SelectJsContract,
    Submit as SubmitContract,
    Suggest as SuggestContract,
    Text as TextContract,
    Textarea as TextareaContract,
    TextRemaining as TextRemainingContract,
    ToggleSwitch as ToggleSwitchContract};
use tiFy\Field\Fields\{
    Button\Button,
    Checkbox\Checkbox,
    CheckboxCollection\CheckboxCollection,
    Colorpicker\Colorpicker,
    File\File,
    FileJs\FileJs,
    DatetimeJs\DatetimeJs,
    Hidden\Hidden,
    Label\Label,
    Number\Number,
    NumberJs\NumberJs,
    Password\Password,
    PasswordJs\PasswordJs,
    Radio\Radio,
    RadioCollection\RadioCollection,
    Repeater\Repeater,
    Select\Select,
    SelectImage\SelectImage,
    SelectJs\SelectJs,
    Submit\Submit,
    Suggest\Suggest,
    Text\Text,
    Textarea\Textarea,
    TextRemaining\TextRemaining,
    ToggleSwitch\ToggleSwitch};

class FieldServiceProvider extends ServiceProvider
{
    /**
     * Liste des noms de qualification des services fournis.
     * {@internal Permet le chargement différé des services qualifié.}
     * @var string[]
     */
    protected $provides = [
        'field',
        'field.viewer',
        ButtonContract::class,
        CheckboxContract::class,
        CheckboxCollectionContract::class,
        ColorpickerContract::class,
        FileContract::class,
        FileJsContract::class,
        DatetimeJsContract::class,
        HiddenContract::class,
        LabelContract::class,
        NumberContract::class,
        NumberJsContract::class,
        PasswordContract::class,
        PasswordJsContract::class,
        RadioContract::class,
        RadioCollectionContract::class,
        RepeaterContract::class,
        SelectContract::class,
        SelectImageContract::class,
        SelectJsContract::class,
        SubmitContract::class,
        SuggestContract::class,
        TextContract::class,
        TextareaContract::class,
        TextRemainingContract::class,
        ToggleSwitchContract::class
    ];

    /**
     * @inheritDoc
     */
    public function register()
    {
        $this->getContainer()->share('field', function () {
            return new Field($this->getContainer());
        });

        $this->registerFactories();

        $this->registerViewer();
    }

    /**
     * Déclaration des controleurs de portions d'affichage.
     *
     * @return void
     */
    public function registerFactories(): void
    {
        $this->getContainer()->add(ButtonContract::class, function () {
            return new Button();
        });

        $this->getContainer()->add(CheckboxContract::class, function () {
            return new Checkbox();
        });

        $this->getContainer()->add(CheckboxCollectionContract::class, function () {
            return new CheckboxCollection();
        });

        $this->getContainer()->add(ColorpickerContract::class, function () {
            return new Colorpicker();
        });

        $this->getContainer()->add(FileContract::class, function () {
            return new File();
        });

        $this->getContainer()->add(FileJsContract::class, function () {
            return new FileJs();
        });

        $this->getContainer()->add(DatetimeJsContract::class, function () {
            return new DatetimeJs();
        });

        $this->getContainer()->add(HiddenContract::class, function () {
            return new Hidden();
        });

        $this->getContainer()->add(LabelContract::class, function () {
            return new Label();
        });

        $this->getContainer()->add(NumberContract::class, function () {
            return new Number();
        });

        $this->getContainer()->add(NumberJsContract::class, function () {
            return new NumberJs();
        });

        $this->getContainer()->add(PasswordContract::class, function () {
            return new Password();
        });

        $this->getContainer()->add(PasswordJsContract::class, function () {
            return new PasswordJs();
        });

        $this->getContainer()->add(RadioContract::class, function () {
            return new Radio();
        });

        $this->getContainer()->add(RadioCollectionContract::class, function () {
            return new RadioCollection();
        });

        $this->getContainer()->add(RepeaterContract::class, function () {
            return new Repeater();
        });

        $this->getContainer()->add(SelectContract::class, function () {
            return new Select();
        });

        $this->getContainer()->add(SelectImageContract::class, function () {
            return new SelectImage();
        });

        $this->getContainer()->add(SelectJsContract::class, function () {
            return new SelectJs();
        });

        $this->getContainer()->add(SubmitContract::class, function () {
            return new Submit();
        });

        $this->getContainer()->add(SuggestContract::class, function () {
            return new Suggest();
        });

        $this->getContainer()->add(TextContract::class, function () {
            return new Text();
        });

        $this->getContainer()->add(TextareaContract::class, function () {
            return new Textarea();
        });

        $this->getContainer()->add(TextRemainingContract::class, function () {
            return new TextRemaining();
        });

        $this->getContainer()->add(ToggleSwitchContract::class, function () {
            return new ToggleSwitch();
        });
    }

    /**
     * Déclaration du controleur d'affichage.
     *
     * @return void
     */
    public function registerViewer(): void
    {
        $this->getContainer()->add('field.viewer', function (FieldFactory $factory) {
            /** @var FieldContract $manager */
            $manager = $this->getContainer()->get('field');

            $directory = $factory->get(
                'viewer.directory',
                $manager->resourcesDir("/views/{$factory->getAlias()}")
            );
            $override_dir = $factory->get('viewer.override_dir');

            return view()
                ->setDirectory(is_dir($directory) ? $directory : null)
                ->setController(FieldView::class)
                ->setOverrideDir((($override_dir) && is_dir($override_dir))
                    ? $override_dir
                    : (is_dir($directory) ? $directory : __DIR__)
                )
                ->set('field', $factory);
        });
    }
}