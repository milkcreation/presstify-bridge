<?php
/**
 * Liste des groupes de champs.
 * ---------------------------------------------------------------------------------------------------------------------
 * @var tiFy\Contracts\Form\FactoryView $this
 * @var tiFy\Contracts\Form\FactoryGroup[] $groups
 * @var tiFy\Contracts\Form\FactoryGroup $group
 */
?>
<?php foreach ($groups as $name => $group) : ?>
    <?php $this->insert('group', compact('group')); ?>
<?php endforeach; ?>