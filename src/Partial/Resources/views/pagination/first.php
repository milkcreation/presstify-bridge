<?php
/**
 * Pagination - Lien vers la première page.
 * ---------------------------------------------------------------------------------------------------------------------
 * @var tiFy\Partial\Driver\Pagination\PaginationView $this
 */
?>
<?php if ($this->getPage() > 1) : ?>
    <li class="Pagination-item Pagination-item--first">
        <?php echo partial('tag', $this->get('links.first')); ?>
    </li>
<?php endif;