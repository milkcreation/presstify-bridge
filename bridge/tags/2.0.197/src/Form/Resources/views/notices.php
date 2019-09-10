<?php
/**
 * Affichage des messages de notification.
 * ---------------------------------------------------------------------------------------------------------------------
 * @var tiFy\Contracts\Form\FactoryView $this
 */
?>
<?php
if ($errors = $this->get('notices.error', [])) :
    echo partial('notice', [
        'type'    => 'error',
        'content' => $this->fetch('notices-error', ['messages' => $errors])
    ]);
elseif ($success = $this->get('notices.success', [])) :
    echo partial('notice', [
        'type'    => 'success',
        'content' => $this->fetch('notices-success', ['messages' => $success])
    ]);
endif;