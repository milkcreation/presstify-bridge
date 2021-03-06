<?php
/**
 * Affichage des messages de notification.
 * ---------------------------------------------------------------------------------------------------------------------
 * @var tiFy\Contracts\Form\FactoryView $this
 */
if ($errors = $this->get('notices.error', [])) :
    echo partial('notice', [
        'attrs'   => [
           'class' => 'FormNotice FormNotice--error'
        ],
        'type'    => 'error',
        'content' => $this->fetch('notices-error', ['messages' => $errors])
    ]);
elseif ($success = $this->get('notices.success', [])) :
    echo partial('notice', [
        'attrs'   => [
            'class' => 'FormNotice FormNotice--success'
        ],
        'type'    => 'success',
        'content' => $this->fetch('notices-success', ['messages' => $success])
    ]);
endif;