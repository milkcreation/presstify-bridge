<?php
/**
 * Pagination - Lien vers la page précédente.
 * ---------------------------------------------------------------------------------------------------------------------
 * @var tiFy\Partial\Driver\Pagination\PaginationView $this
 */
?>
<?php if ($this->getPage() > 1) : ?>
    <li class="Pagination-item Pagination-item--previous">
        <?php echo partial('tag', $this->get('links.previous')); ?>
    </li>
<?php endif;