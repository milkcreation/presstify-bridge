<?php
/**
 * Pagination - Lien vers la page suivante.
 * ---------------------------------------------------------------------------------------------------------------------
 * @var tiFy\Partial\Driver\Pagination\PaginationView $this
 */
?>
<?php if ($this->getCurrentPage() < $this->getLastPage()) : ?>
    <li class="Pagination-item Pagination-item--next">
        <?php echo partial('tag', $this->get('links.next')); ?>
    </li>
<?php endif;