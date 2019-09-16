<?php
/**
 * @var tiFy\Contracts\View\ViewController $this
 */
?>

<div class="MetaboxOptions-slideshowListItemHelpers">
    <div class="MetaboxOptions-slideshowListItemHelper MetaboxOptions-slideshowListItemHelper--order">
        <?php
        echo field(
            'text',
            [
                'name'  => "{$this->get('name')}[order]",
                'value' => $this->get('order'),
                'attrs' => [
                    'readonly'
                ]
            ]
        );
        ?>
    </div>

    <a href="#sort" class="MetaboxOptions-slideshowListItemHelper MetaboxOptions-slideshowListItemHelper--sort"></a>

    <a href="#order" class="MetaboxOptions-slideshowListItemHelper MetaboxOptions-slideshowListItemHelper--remove ThemeButton--remove"></a>

    <span class="MetaboxOptions-slideshowListItemHelper MetaboxOptions-slideshowListItemHelper--infos">
        <?php echo $this->get('post_id') ? __('Contenu du site', 'tify') : __('Vignette personnalisée', 'tify'); ?>
    </span>
</div>