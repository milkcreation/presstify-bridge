<?php
/**
 * @var tiFy\Contracts\Metabox\MetaboxView $this
 */
?>
<h3 class="MetaboxSlidefeed-itemFieldLabel"><?php _e('Titre', 'tify'); ?></h3>
<?php echo field('text', [
    'name'  => $this->get('name') . '[title]',
    'value' => $this->get('value.title')
]);