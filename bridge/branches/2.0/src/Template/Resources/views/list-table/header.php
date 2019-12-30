<?php
/**
 * Entête.
 * ---------------------------------------------------------------------------------------------------------------------
 * @var tiFy\Template\Templates\ListTable\View $this
 */
?>
<?php if($page_title = $this->label('page_title')) : ?>
<h1 class="wp-heading-inline">
    <?php echo $page_title; ?>
</h1>
<?php endif; ?>