<?php
/**
 * Pagination - Lien vers la dernière page.
 * ---------------------------------------------------------------------------------------------------------------------
 * @var tiFy\Partial\Driver\Pagination\PaginationView $this
 */
?>
<?php if ($this->getCurrentPage() < $this->getLastPage()) : ?>
    <li class="Pagination-item Pagination-item--last">
        <?php echo partial('tag', $this->get('links.last')); ?>
    </li>
<?php endif;