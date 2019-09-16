<?php
/**
 * @var tiFy\Contracts\View\ViewController $this
 */
?>

<div class="MetaboxOptions-slideshowListItemInput MetaboxOptions-slideshowListItemInput--title">
    <h3><?php _e('Lien', 'tify'); ?></h3>

    <label>
        <?php
        $hide_unchecked = 'MetaboxOptions-slideshowListItemInput--link';
        echo field(
            'checkbox',
            [
                'name'    => "{$this->get('name')}[clickable]",
                'value'   => 1,
                'checked' => $this->get('clickable', 0),
                'attrs'   => [
                    'autocomplete'        => 'off',
                    'data-hide_unchecked' => '.' . $hide_unchecked
                ],
                'after'   => __('Vignette cliquable', 'tify')
            ]
        );
        ?>
    </label>

    <?php
    $attrs = $this->get('post_id') ? ['readonly'] : [];
    $attrs['placeholder'] = __('Saisissez l\'url au clic.', 'tify');
    $attrs['class'] = "%s $hide_unchecked";

    echo field(
        'text',
        [
            'name'  => "{$this->get('name')}[url]",
            'value' => $this->get('url'),
            'attrs' => $attrs
        ]
    );
    ?>
</div>