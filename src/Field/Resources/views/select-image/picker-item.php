<?php
/**
 * Affichage d'un élément dans la liste de sélection.
 * ---------------------------------------------------------------------------------------------------------------------
 * @var tiFy\Field\FieldView $this
 * @var tiFy\Contracts\Field\SelectChoice $item
 */
?>
<img src="<?php echo $item->getContent(); ?>" alt="<?php echo $item->getName(); ?>" />