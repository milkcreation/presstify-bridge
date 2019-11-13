<?php
/**
 * @var tiFy\Field\FieldView $this
 */
?>
<?php $this->before(); ?>
    <div <?php $this->attrs(); ?>>
        <div class="tiFyField-toggleSwitchWrapper">
            <?php echo field('radio', [
                'after'   => (string)field('label', [
                    'content' => $this->get('label_on'),
                    'attrs'   => [
                        'for'   => $this->getId() . '--on',
                        'class' => 'tiFyField-toggleSwitchLabel tiFyField-toggleSwitchLabel--on',
                    ],
                ]),
                'attrs'   => [
                    'id'           => $this->getId() . '--on',
                    'class'        => 'tiFyField-toggleSwitchRadio tiFyField-toggleSwitchRadio--on',
                    'autocomplete' => 'off',
                ],
                'name'    => $this->getName(),
                'value'   => $this->get('value_on'),
                'checked' => $this->getValue(),
            ]); ?>

            <?php echo field('radio', [
                'after'   => (string)field('label', [
                    'content' => $this->get('label_off'),
                    'attrs'   => [
                        'for'   => $this->getId() . '--off',
                        'class' => 'tiFyField-toggleSwitchLabel tiFyField-toggleSwitchLabel--off',
                    ],
                ]),
                'attrs'   => [
                    'id'           => $this->getId() . '--off',
                    'class'        => 'tiFyField-toggleSwitchRadio tiFyField-toggleSwitchRadio--off',
                    'autocomplete' => 'off',
                ],
                'name'    => $this->getName(),
                'value'   => $this->get('value_off'),
                'checked' => $this->getValue(),
            ]); ?>

            <span class="tiFyField-toggleSwitchHandler"></span>
        </div>
    </div>
<?php $this->after();