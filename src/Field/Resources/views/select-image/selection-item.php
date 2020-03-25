<?php
/**
 * Affichage d'un élément sélectionné.
 * ---------------------------------------------------------------------------------------------------------------------
 * @var tiFy\Field\FieldView $this
 * @var tiFy\Contracts\Field\SelectChoice $item
 */
?>
<img src="<?php echo $item->getContent(); ?>" alt="<?php echo $item->getName(); ?>" />