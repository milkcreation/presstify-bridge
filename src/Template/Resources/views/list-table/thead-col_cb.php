<?php
/**
 * Entête de la table.
 * ---------------------------------------------------------------------------------------------------------------------
 * @var tiFy\Template\Templates\ListTable\View $this
 * @var string $attrs Liste des attributs de balise HTML.
 * @var int $index Numero d'instance d'affichage
 */
?>
<td <?php echo $this->get('attrs', ''); ?>>
    <?php echo field('label', [
        'attrs'   => [
            'class' => 'screen-reader-text',
            'for'   => 'cb-select-all-' . $this->get('index')
        ],
        'content' => __('Select All')
    ]); ?>

    <?php echo field('checkbox', [
        'attrs' => [
            'id' => 'cb-select-all-' . $this->get('index')
        ]
    ]); ?>
</td>