<?php
/**
 * Options du message de notification de réception de la demande de contact.
 * ---------------------------------------------------------------------------------------------------------------------
 * @var tiFy\Contracts\Form\FactoryView $this
 */
?>


    <table class="form-table">
        <tbody>
        <tr>
            <th scope="row"><?php _e('Activation', 'tify'); ?></th>
            <td>
                <?php
                echo field(
                    'toggle-switch',
                    [
                        'name'  => $this->get('option_names.notification'),
                        'value' => $this->get('option_values.notification')
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
        _e('Message de notification de réception d\'une demande de contact, envoyé aux administrateurs de site.',
            'tify');
        ?>
    </em>

    <h3><?php _e('Liste des administrateurs de site (destinataires)', 'tify'); ?></h3>

<?php
echo field(
    'repeater',
    [
        'button' => [
            'content' => __('Ajouter un destinataire', 'tify')
        ],
        'name'   => $this->get('option_names.recipients'),
        'value'  => $this->get('option_values.recipients'),
        'viewer' => [
            'override_dir' => dirname($this->path()) . '/repeater',
        ],
    ]
);
