<?php
/**
 * Interface de pagination de la table.
 * ---------------------------------------------------------------------------------------------------------------------
 * @var tiFy\Template\Templates\ListTable\Viewer $this
 * @var string $which top|bottom.
 */
?>
<?php if ($total = $this->pagination()->getTotal()) : ?>
    <?php $pagination = $this->pagination()->setWhich($which ?? 'top'); ?>
    <div <?php echo $this->htmlAttrs($pagination->get('attrs', [])); ?>>
        <span class="displaying-num">
            <?php printf(
                _n('%s élément', '%s éléments', $total, 'tify'),
                number_format_i18n($total)
            );
            ?>
        </span>
        <span class="pagination-links">
            <?php $this->insert('pagination-first'); ?>
            <?php $this->insert('pagination-prev'); ?>
            <?php $this->insert('pagination-current'); ?>
            <?php $this->insert('pagination-next'); ?>
            <?php $this->insert('pagination-last'); ?>
        </span>
    </div>
<?php endif;