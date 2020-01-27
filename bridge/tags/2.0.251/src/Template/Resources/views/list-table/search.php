<?php
/**
 * Champ de recherche.
 * ---------------------------------------------------------------------------------------------------------------------
 * @var tiFy\Template\Templates\ListTable\View $this
 */
?>
<p <?php echo $this->htmlAttrs($this->search()->get('attrs', [])); ?>>
    <?php echo field('label', [
        'attrs'   => [
            'class' => 'screen-reader-text',
            'for'   => $this->name(),
        ],
        'content' => $this->label('search_item'),
    ]); ?>
    <?php echo field('text', $this->search()->get('input',  [])); ?>
    <?php echo partial('tag', $this->search()->get('submit',  [])); ?>
</p>