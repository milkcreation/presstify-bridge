<?php
/**
 * Pagination - Lien vers la page suivante.
 * ---------------------------------------------------------------------------------------------------------------------
 * @var tiFy\Partial\Partials\Pagination\PaginationView $this
 */
?>

<?php if ($this->getPage() < $this->getTotalPage()) : ?>
    <li class="Pagination-item Pagination-item--next">
        <?php echo partial('tag', $this->get('links.next')); ?>
    </li>
<?php endif;