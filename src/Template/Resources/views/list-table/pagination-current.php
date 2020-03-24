<?php
/**
 * Pagination - Accès à la dernière page.
 * ---------------------------------------------------------------------------------------------------------------------
 * @var tiFy\Template\Templates\ListTable\View $this
 */
?>
<?php if ($this->pagination()->getWhich() === 'top') : ?>
    <span class="paging-input">
        <label for="current-page-selector" class="screen-reader-text">
            <?php _e('Page actuelle', 'tify'); ?>
        </label>

        <?php echo field('text', [
            'attrs' => [
                'aria-describedby' => 'table-paging',
                'class' => 'current-page',
                'id'    => 'current-page-selector',
                'size'  => 3
            ],
            'name'  => 'paged',
            'value' => $this->pagination()->getCurrentPage()
        ]); ?>

       <span class="tablenav-paging-text">
           &nbsp;<?php _e('sur', 'tify'); ?>&nbsp;
           <span class="total-pages"><?php echo $this->pagination()->getLastPage(); ?></span>
       </span>
    </span>
<?php else: ?>
    <span id="table-paging" class="paging-input">
        <span class="tablenav-paging-text">
            <?php echo $this->pagination()->getCurrentPage(); ?>&nbsp;<?php _e('sur', 'tify'); ?>&nbsp;
            <span class="total-pages"><?php echo $this->pagination()->getLastPage(); ?></span>
        </span>
    </span>
<?php endif;