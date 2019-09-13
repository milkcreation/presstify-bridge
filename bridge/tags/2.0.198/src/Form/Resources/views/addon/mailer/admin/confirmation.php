<?php
/**
 * Options du message de confirmation de réception de la demande de contact.
 * ---------------------------------------------------------------------------------------------------------------------
 * @var tiFy\Contracts\Form\FactoryView $this
 */
?>
<table class="form-table">
    <tbody>
    <tr>
        <th scope="row">
            <?php _e('Activation', 'tify'); ?>
        </th>
        <td>
            <?php
            echo field(
                'toggle-switch',
                [
                    'name'  => $this->get('option_names.confirmation'),
                    'value' => $this->get('option_values.confirmation')
                ]
            );
            ?>
        </td>
    </tr>
    </tbody>
</table>

<em>
    <span class="dashicons dashicons-flag"></span>
    <?php
    _e('Message de confirmation de réception de la demande de contact, envoyé à l\'émetteur de la demande.', 'tify');
    ?>
</em>

<h3><?php _e('Expéditeur de l\'email envoyé à l\'émetteur', 'tify'); ?></h3>

<table class="form-table">
    <tbody>
    <tr>
        <th scope="row">
            <?php _e('Email (requis)', 'tify'); ?>
        </th>
        <td>
            <div class="ThemeInput--email">
                <?php
                echo field(
                    'text',
                    [
                        'name'  => $this->get('option_names.sender') . '[email]',
                        'value' => $this->get('option_values.sender.email'),
                        'attrs' => [
                            'placeholder'  => __('Email (requis)', 'tify'),
                            'size'         => 40,
                            'autocomplete' => 'off'
                        ]
                    ]
                );
                ?>
            </div>
        </td>
    </tr>
    <tr>
        <th scope="row">
            <?php _e('Nom (optionnel)', 'tify'); ?>
        </th>
        <td>
            <div class="ThemeInput--user">
                <?php
                echo field(
                    'text',
                    [
                        'name'  => $this->get('option_names.sender') . '[name]',
                        'value' => $this->get('option_values.sender.name'),
                        'attrs' => [
                            'placeholder'  => __('Nom (optionnel)', 'tify'),
                            'size'         => 40,
                            'autocomplete' => 'off'
                        ]
                    ]
                );
                ?>
            </div>
        </td>
    </tr>
    </tbody>
</table>