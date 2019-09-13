<?php
/**
 * @var tiFy\Field\FieldView $this
 */
?>
<?php $this->before(); ?>

    <div <?php $this->attrs(); ?>>
        <a href="#tiFyField-mediaImageAdd--<?php echo $this->getIndex(); ?>"
           id="tiFyField-mediaImageAdd--<?php echo $this->getIndex(); ?>" class="tiFyField-mediaImageAdd"
           title="<?php _e('Modification de l\'image', 'tify'); ?>"
           style="background-image:url(<?php echo $this->get('value_img', ''); ?>);
                   width:100%;
                   padding-top: <?php echo 100 * ($this->get('height') / $this->get('width')) . "%;"; ?>
                <?php echo $this->get('editable', true) ? 'cursor:pointer;' : 'cursor:default;'; ?>"
        >
            <?php if ($this->get('editable', true)) : ?>
                <i class="tiFyField-mediaImageAddIco"></i>
            <?php endif; ?>
        </a>

        <?php if ($info_txt = $this->get('info_txt', '')) : ?>
            <span class="tiFyField-mediaImageSize"><?php echo $info_txt; ?></span>
        <?php endif; ?>

        <?php if ($content = $this->get('content', '')) : ?>
            <div class="tiFyField-mediaImageContent"><?php echo $content; ?></div>
        <?php endif; ?>

        <input type="hidden" class="tiFyField-mediaImageInput" name="<?php echo $this->get('name', ''); ?>"
               value="<?php echo $this->getValue(); ?>"/>

        <?php if ($this->get('removable', true)) : ?>
            <a href="#<?php $this->get('attrs.id', ''); ?>" class="tiFyField-mediaImageRemove ThemeButton--remove"></a>
        <?php endif; ?>
    </div>

<?php $this->after();