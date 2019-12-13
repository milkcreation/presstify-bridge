<?php
/**
 * @var tiFy\Contracts\View\ViewController $this
 */
?>
<div style="position:relative;width:100%;height:0;padding-bottom:<?php echo $this->get('ratio'); ?>%;">
    <iframe style="position:absolute;top:0;left:0;width:100%;height:100%;"
            width="<?php echo $this->get('width'); ?>"
            height="<?php echo $this->get('height'); ?>"
            src="<?php echo $this->get('src'); ?>"
            frameborder="0"
            allow="autoplay; encrypted-media"
        <?php if ($this->get('params.fs')) : ?>
            allowfullscreen
        <?php endif; ?>
    ></iframe>
</div>