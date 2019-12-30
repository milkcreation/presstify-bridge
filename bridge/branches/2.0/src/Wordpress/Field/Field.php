<?php declare(strict_types=1);

namespace tiFy\Wordpress\Field;

use tiFy\Contracts\Field\{
    Colorpicker as ColorpickerContract,
    Field as Manager,
    DatetimeJs as DatetimeJsContract,
    FileJs as FileJsContract,
    NumberJs as NumberJsContract,
    PasswordJs as PasswordJsContract,
    Repeater as RepeaterContract,
    SelectImage as SelectImageContract,
    SelectJs as SelectJsContract,
    Suggest as SuggestContract,
    TextRemaining as TextRemainingContract,
    Tinymce as TinymceContract,
    ToggleSwitch as ToggleSwitchContract};
use tiFy\Wordpress\Contracts\Field\{
    Findposts as FindpostsContract,
    MediaFile as MediaFileContract,
    MediaImage as MediaImageContract};
use tiFy\Wordpress\Field\Driver\{
    Colorpicker\Colorpicker,
    DatetimeJs\DatetimeJs,
    FileJs\FileJs,
    Findposts\Findposts,
    MediaFile\MediaFile,
    MediaImage\MediaImage,
    NumberJs\NumberJs,
    PasswordJs\PasswordJs,
    Repeater\Repeater,
    SelectImage\SelectImage,
    SelectJs\SelectJs,
    Suggest\Suggest,
    TextRemaining\TextRemaining,
    Tinymce\Tinymce,
    ToggleSwitch\ToggleSwitch
};

class Field
{
    /**
     * Définition des déclarations des champs spécifiques à Wordpress.
     * @var array
     */
    protected $register = [
        'findposts'           => FindpostsContract::class,
        'media-file'          => MediaFileContract::class,
        'media-image'         => MediaImageContract::class,
    ];

    /**
     * Instance du gestionnaire des champs.
     * @var Manager
     */
    protected $manager;

    /**
     * CONSTRUCTEUR
     *
     * @param Manager $manager Instance du gestionnaire des champs.
     *
     * @return void
     */
    public function __construct(Manager $manager)
    {
        $this->manager = $manager;

        $this->register();
        $this->registerOverride();

        $this->manager->registerDefaults();
        foreach ($this->register as $name => $alias) {
            $this->manager->set($name, $this->manager->getContainer()->get($alias));
        }
    }

    /**
     * Déclaration des controleurs de champs spécifiques à Wordpress.
     *
     * @return void
     */
    public function register(): void
    {
        app()->add(FindpostsContract::class, function () {
            return new Findposts();
        });

        app()->add(MediaFileContract::class, function () {
            return new MediaFile();
        });

        app()->add(MediaImageContract::class, function () {
            return new MediaImage();
        });
    }

    /**
     * Déclaration des controleurs de surchage des champs.
     *
     * @return void
     */
    public function registerOverride(): void
    {
        app()->add(ColorpickerContract::class, function () {
            return new Colorpicker();
        });

        app()->add(DatetimeJsContract::class, function () {
            return new DatetimeJs();
        });

        app()->add(FileJsContract::class, function () {
            return new FileJs();
        });

        app()->add(NumberJsContract::class, function () {
            return new NumberJs();
        });

        app()->add(PasswordJsContract::class, function () {
            return new PasswordJs();
        });

        app()->add(RepeaterContract::class, function () {
            return new Repeater();
        });

        app()->add(SelectImageContract::class, function () {
            return new SelectImage();
        });

        app()->add(SelectJsContract::class, function () {
            return new SelectJs();
        });

        app()->add(SuggestContract::class, function () {
            return new Suggest();
        });

        app()->add(TextRemainingContract::class, function () {
            return new TextRemaining();
        });

        app()->add(TinymceContract::class, function () {
            return new Tinymce();
        });

        app()->add(ToggleSwitchContract::class, function () {
            return new ToggleSwitch();
        });
    }
}