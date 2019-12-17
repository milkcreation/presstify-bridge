<?php
/**
 * Vue filtrées.
 * ---------------------------------------------------------------------------------------------------------------------
 * @var tiFy\Template\Templates\ListTable\Viewer $this
 */
?>
<?php if ($this->viewFilters()->exists()) : ?>
    <ul class='subsubsub'>
        <?php foreach ($this->viewFilters() as $name => $filter) : ?>
            <li class="<?php echo $name; ?>"><?php echo $filter; ?></li>
        <?php endforeach; ?>
    </ul>
<?php endif;