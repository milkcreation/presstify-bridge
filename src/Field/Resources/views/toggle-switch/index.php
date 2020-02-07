<?php
/**
 * @var tiFy\Field\FieldView $this
 */
?>
<?php $this->before(); ?>
    <div <?php $this->attrs(); ?>>
        <div class="FieldToggleSwitch-wrapper">
            <?php echo field('radio', [
                'after'   => (string)field('label', [
                    'content' => $this->get('label_on'),
                    'attrs'   => [
                        'for'   => $this->getId() . '--on',
                        'class' => 'FieldToggleSwitch-label FieldToggleSwitch-label--on',
                    ],
                ]),
                'attrs'   => [
                    'id'           => $this->getId() . '--on',
                    'class'        => 'FieldToggleSwitch-radio FieldToggleSwitch-radio--on',
                    'autocomplete' => 'off',
                ],
                'name'    => $this->getName(),
                'value'   => $this->getValue(),
                'checked' => $this->get('value_on'),
            ]); ?>

            <?php echo field('radio', [
                'after'   => (string)field('label', [
                    'content' => $this->get('label_off'),
                    'attrs'   => [
                        'for'   => $this->getId() . '--off',
                        'class' => 'FieldToggleSwitch-label FieldToggleSwitch-label--off',
                    ],
                ]),
                'attrs'   => [
                    'id'           => $this->getId() . '--off',
                    'class'        => 'FieldToggleSwitch-radio FieldToggleSwitch-radio--off',
                    'autocomplete' => 'off',
                ],
                'name'    => $this->getName(),
                'value'   => $this->getValue(),
                'checked' => $this->get('value_off'),
            ]); ?>

            <span class="FieldToggleSwitch-handler"></span>
        </div>
    </div>
<?php $this->after();