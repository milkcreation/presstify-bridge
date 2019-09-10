<?php
/**
 * @var tiFy\Contracts\View\ViewController $this
 */
?>

<div <?php echo $this->htmlAttrs($this->get('attrs', [])); ?>>
    <div class="MetaboxOptions-slideshowSelectors">
        <?php
        /**
         * @todo

        if ($this->get('suggest')) :
            echo field(
                'select-js',
                [
                    'max'          => 1,
                    'removable'    => false,
                    'sortable'     => false,
                    'autocomplete' => true,
                    'source'       => [
                        'query_args' => [
                            'post_type'      => 'post',
                            'posts_per_page' => 2
                        ]
                    ]
                ]
            );
        endif;
        ?>

        <?php if ($this->get('suggest') && $this->get('custom')) : ?>
                <p><?php _e('ou', 'tify'); ?></p>
        <?php endif; */ ?>

        <?php
        if ($this->get('custom')) :
            echo field(
                'button',
                [
                    'before'  => '<div>',
                    'after'   => '</div>',
                    'attrs'   => [
                        'class' => 'MetaboxOptions-slideshowSelector MetaboxOptions-slideshowSelector--custom button-secondary'
                    ],
                    'content' => __('Vignette personnalisée', 'tify')
                ]
            );
        endif;
        ?>
    </div>

    <div class="MetaboxOptions-slideshowList">
        <div class="MetaboxOptions-slideshowListOverlay">
            <?php _e('Chargement ...', 'tify'); ?>
        </div>

        <?php
        foreach ($this->get('options', []) as $k => $v) :
            echo field(
                'hidden',
                [
                    'name'  => "{$this->get('name')}[options][{$k}]",
                    'value' => $v
                ]
            );
        endforeach;
        ?>

        <ul class="MetaboxOptions-slideshowListItems">
            <?php
            foreach ($this->get('items', []) as $item) :
                echo $item;
            endforeach;
            ?>
        </ul>
    </div>
</div>
