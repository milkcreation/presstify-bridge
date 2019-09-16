<?php
/**
 * @var tiFy\Contracts\View\ViewController $this
 */
?>

<li class="MetaboxOptions-slideshowListItem">
    <div class="MetaboxOptions-slideshowListItemInputs">
        <?php
        echo field(
            'hidden',
            [
                'name'      => "{$this->get('name')}[post_id]",
                'value'     => $this->get('post_id')
            ]
        );
        ?>
        <?php
            foreach($this->get('editable', []) as $edit) :
                $this->insert("item-{$edit}", $this->all());
            endforeach;
        ?>
    </div>

    <?php $this->insert('item-helpers', $this->all()); ?>
</li>