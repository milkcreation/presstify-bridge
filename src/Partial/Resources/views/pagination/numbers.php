<?php
/**
 * Pagination - Liste des numéros de page.
 * ---------------------------------------------------------------------------------------------------------------------
 * @var tiFy\Partial\Partials\Pagination\PaginationView $this
 */
?>
<?php foreach ($this->get('numbers', []) as $number) : ?>
    <li class="Pagination-item Pagination-item--num">
        <?php echo partial('tag', $number); ?>
    </li>
<?php endforeach;