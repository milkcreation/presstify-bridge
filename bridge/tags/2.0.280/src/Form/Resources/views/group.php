<?php
/**
 * Liste des groupes de champs.
 * ---------------------------------------------------------------------------------------------------------------------
 * @var tiFy\Contracts\Form\FactoryView $this
 * @var tiFy\Contracts\Form\FactoryGroup $group
 */
?>
<?php echo $group->before(); ?>
<div <?php echo $group->getAttrs(); ?>>
    <?php foreach ($group->getFields() as $field) : ?>
        <?php $this->insert('field', compact('field')); ?>
    <?php endforeach; ?>
    <?php foreach ($group->getChilds() as $child) : ?>
        <?php $this->insert('group', ['group' => $child]); ?>
    <?php endforeach; ?>
</div>
<?php echo $group->after(); ?>