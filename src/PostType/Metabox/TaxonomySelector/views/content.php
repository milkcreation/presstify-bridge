<?php
/**
 * @var tiFy\Contracts\View\ViewController $this
 */
?>

<?php foreach ($this->get('taxonomy', []) as $tax) : ?>
    <?php echo field('hidden', ['name' => "tax_input[{$tax}][]", 'value' => '']); ?>
<?php endforeach; ?>


<?php if ($this->get('multiple', true)) : ?>
    <?php
    echo field(
        'checkbox-collection',
        [
            'choices' => $this->get('items', []),
            'name'    => $this->get('name'),
            'value'   => $this->get('value')
        ]
    );
    ?>
<?php else : ?>
    <?php
    echo field(
        'radio-collection',
        [
            'choices' => $this->get('items', []),
            'name'    => $this->get('name'),
            'value'   => $this->get('value')
        ]
    );
    ?>
<?php endif; ?>
